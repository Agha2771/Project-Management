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
use ProjectManagement\Services\PaymentService;

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

    public function index()
    {
        $payments = $this->paymentRepository->fetch_all();
        return $this->successResponse(PaymentResource::collection($payments), ResponseMessage::OK , Response::HTTP_OK);
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
        return $this->successResponse(new PaymentResource($payment), ResponseMessage::OK, Response::HTTP_OK);
    }

    // public function update(UpdatePaymentRequest $request, $id)
    // {
    //     $data = $request->prepareRequest();
    //     $payment = $this->paymentRepository->find($id);
    //     $remaining_amount = $payment->remaining_amount;
    //     $status = $payment->status;

    //     if (!$payment) {
    //         return $this->failureResponse('Payment not found!', Response::HTTP_NOT_FOUND);
    //     }
    //     if(isset($data['amount_paid'])){
    //         $project_budged = $payment->budget;
    //         $lastest_payment = $this->paymentRepository->getPaymentAgainstClient($data['project_id'] , $data['user_id']);
    //         $remaining_amount = $lastest_payment->remaining_amount - $data['amount_paid'];
    //         dd($remaining_amount);
    //         if($data['amount_paid'] > $remaining_amount){
    //             return $this->failureResponse('Paid amount should be less than or equal to the remaining amount!');

    //         }else if($data['amount_paid'] < $remaining_amount){
    //             $status = 'patrial';
    //         }else{
    //             $status = 'full';
    //         }
    //     }

    //     $updated_payment = $this->paymentRepository->update($id, array_merge($data, [
    //         'status' => $status,
    //         'remaining_amount' => $remaining_amount,
    //     ]));

    //     return $this->successResponse(new PaymentResource($updated_payment), ResponseMessage::OK, Response::HTTP_OK);
    // }



    public function destroy($id)
    {
        $this->paymentRepository->delete($id);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);
    }

    public function generatePDF(PaymentService $paymentService , $id){

        $payment = $this->paymentRepository->find($id);
        $data['id'] = $payment->id;
        $data['remaining_balance'] = $payment->remaining_amount;
        $data['amount_paid'] =  $payment->project ? $payment->project->budget - $payment->remaining_amount : $payment->amount_paid;
        $data['client_name'] = $payment->user->name;
        $data['project_name'] =  $payment->project ? $payment->project->title : 'Unknown';
        $data['total_budget'] =  $payment->project ? $payment->project->budget : '0';
        $data['currency'] = $payment->project ? $payment->project->currency : 'PKR';
        $paymentService->generatePaymentPdf($data);
     }

}
