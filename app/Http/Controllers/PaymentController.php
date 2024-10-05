<?php

namespace  App\Http\Controllers;

use ProjectManagement\Repositories\Invoice\InvoiceRepositoryInterface;
use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Payment\PaymentRepositoryInterface;
use ProjectManagement\Repositories\Project\ProjectRepositoryInterface;
use ProjectManagement\Resources\PaymentResource;
use ProjectManagement\ValidationRequests\CreatePaymentRequest;
use ProjectManagement\ValidationRequests\UpdatePaymentRequest;
use  ProjectManagement\Models\ProjectAttachment;
use Illuminate\Http\Request;
use App\Helpers\helper;


class PaymentController extends Controller
{
    protected $paymentRepository;
    protected $projectRepository;
    protected $invoiceRepository;
    use ApiResponseTrait;

    public function __construct(PaymentRepositoryInterface $paymentRepository, ProjectRepositoryInterface $projectRepository , InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->projectRepository = $projectRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function index(Request $request , $client_id = null)
    {
        if (!$request->has('page_num') && !isset( $client_id)) {
            $invoices = $this->paymentRepository->fetch_all(null);
            return $this->successResponse(PaymentResource::collection($invoices), ResponseMessage::OK , Response::HTTP_OK);
        }else if (!$request->has('page_num') && isset( $client_id)){
            $invoices = $this->paymentRepository->fetch_all($client_id);
            return $this->successResponse(PaymentResource::collection($invoices), ResponseMessage::OK , Response::HTTP_OK);
        }else{
        $per_page = $request->input('page_size', 10);
        $page_mum = $request->input('page_num', 1);
        $search = $request->input('search', '');
        $sort_by = $request->input('sortBy', 'desc');
        $payments = $this->paymentRepository->paginate($per_page, ['*'], 'page', $page_mum, $search , $sort_by);
        return $this->successResponse($payments, ResponseMessage::OK, Response::HTTP_OK);
    }
    }
    public function create(CreatePaymentRequest $request)
    {
        $data = $request->prepareRequest();
        $invoice = $this->invoiceRepository->find($data['invoice_id']);
        $invoice_amount = $invoice->amount;
        $latest_payment = $this->paymentRepository->getPaymentAgainstClient($data['invoice_id']);
        if ($latest_payment){
            $payment_remaining_amount = $latest_payment->remaining_amount;
            if($payment_remaining_amount == 0){
                return $this->successResponse('Your amount is fully paid!' , ResponseMessage::OK, Response::HTTP_OK);
            }else{
                if($data['amount_paid'] > $payment_remaining_amount){
                    return $this->failureResponse('Paid amount should be less than or equal to the remaining amount!');
                }else{
                    $remaining_amount = $payment_remaining_amount - $data['amount_paid'];
                    $status = $remaining_amount == 0 ? 'full' : 'partial';
                }
            }
        }else{
            if($invoice_amount < $data['amount_paid']){
                return $this->failureResponse('Paid amount should be less than or equal to the remaining amount!');
            }else if ($invoice_amount == $data['amount_paid']){
                $remaining_amount = 0;
                $status = 'full';
            }else{
                $remaining_amount = $invoice_amount-$data['amount_paid'];
                $status = 'partial';
            }
        }
        $payment = $this->paymentRepository->create(array_merge($data, ['status' => $status, 'remaining_amount' => $remaining_amount]));
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            $prepare_data = [
                'item_type' => 'payment',
                'item_id' => $payment->id,
                'files' => $attachments
            ];
            helper::storeAttachments($prepare_data);
        }
        return $this->successResponse(new PaymentResource($payment), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function update(UpdatePaymentRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $payment = $this->paymentRepository->find($id);
        $total_amount = $payment->invoice->amount;
        $allPayments = $payment->invoice->payments->map(function ($p) use ($id , $data) {
            if ($p->id == $id) {
                $p->amount_paid = $data['amount_paid'];
            }
            return $p;
        });
        $payments_amount = $allPayments->sum('amount_paid');
        if ($payments_amount > $total_amount){
            return $this->failureResponse('Paid amount should be less than or equal to the remaining amount!');
        }else{
            $updated_payment = $this->paymentRepository->update($id,$data);
        }
        $data = [
            'invoice_id' => $payment->invoice->hash,
            'amount' => $total_amount,
            'status' => ($updated_payment->invoice->payments()->sum('amount_paid') < $total_amount) ? 'partial' : ($updated_payment->invoice->payments()->sum('amount_paid') === 0 ? 'unpaid' : 'full'),
            'remaining_amount' => $total_amount -$payments_amount ,
            'total_paid' => $payments_amount,
            'payments' => $allPayments
        ];
        return $this->successResponse($data, ResponseMessage::OK, Response::HTTP_OK);
    }


    public function storeAttachments(Request $request)
    {
        $data = [
            'files' => $request->attachments,
            'item_id' => $request->item_id,
            'item_type' => $request->item_type,
        ];
        $array = helper::storeAttachments($data);
        return $this->successResponse($array, ResponseMessage::OK , Response::HTTP_OK);
    }
    public function destroy($id)
    {
        $payment = $this->paymentRepository->find($id);
        if (!$payment) {
            return $this->failureResponse('Payment not found!', Response::HTTP_NOT_FOUND);
        }
        $total_amount = $payment->invoice->amount;
        $remaining_payments = $payment->invoice->payments->filter(function ($p) use ($id) {
            return $p->id != $id;
        });
        $payments_amount = $remaining_payments->sum('amount_paid');
        $data = [
            'invoice_id' => $payment->invoice->hash,
            'amount' => $total_amount,
            'status' => ($payment->invoice->payments()->sum('amount_paid') < $total_amount) ? 'partial' : ($payment->invoice->payments()->sum('amount_paid') === 0 ? 'unpaid' : 'full'),
            'remaining_amount' => $total_amount -$payments_amount,
            'total_paid' => $payments_amount,
            'payments' => $remaining_payments
        ];
        $payment->delete();
        return $this->successResponse($data, ResponseMessage::OK, Response::HTTP_OK);
    }
    public function removeAttachment($attachment_id)
    {
        $attachment = ProjectAttachment::find($attachment_id);
        if ($attachment) {
            $filePath = $attachment->file_path;
            $attachment->delete();
            $fullPath = storage_path('app/public/' . ltrim($filePath, 'storage/'));
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);

        }
            return $this->successResponse('', ResponseMessage::ERROR , Response::HTTP_OK);

    }

}
