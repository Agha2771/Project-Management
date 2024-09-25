<?php

namespace  App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Invoice\InvoiceRepositoryInterface;
use ProjectManagement\Repositories\ProjectExpense\ProjectExpenseRepositoryInterface;
use ProjectManagement\Resources\InvoiceResource;
use ProjectManagement\Resources\InvoicePdfResource;
use Illuminate\Http\Request;
use ProjectManagement\Services\InvoiceService;
use ProjectManagement\ValidationRequests\CreateInvoiceRequest;
use ProjectManagement\ValidationRequests\UpdateInvoiceRequest;

class InvoiceController extends Controller
{
    protected $invoiceRepository;
    protected $projectExpenseRepository;
    use ApiResponseTrait;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository , ProjectExpenseRepositoryInterface $projectExpenseRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->projectExpenseRepository = $projectExpenseRepository;
    }

    public function get_invoices()
    {
        $invoices = $this->invoiceRepository->fetch_all(null);
        return $this->successResponse(InvoiceResource::collection($invoices), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function getInvoices(Request $request , $client_id = null)
    {
        if (!$request->has('page_num') && !isset( $client_id)) {
            $invoices = $this->invoiceRepository->fetch_all(null);
            return $this->successResponse(InvoiceResource::collection($invoices), ResponseMessage::OK , Response::HTTP_OK);
        }else if (!$request->has('page_num') && isset( $client_id)){
            $invoices = $this->invoiceRepository->fetch_all($client_id);
            return $this->successResponse(InvoiceResource::collection($invoices), ResponseMessage::OK , Response::HTTP_OK);
        }else{
        $per_page = $request->input('page_size', 10);
        $page_mum = $request->input('page_num', 1);
        $search = $request->input('search', '');
        $sort_by = $request->input('sortBy', 'desc');
        $projects = $this->invoiceRepository->paginate($per_page, ['*'], 'page', $page_mum, $search , $sort_by);
        return $this->successResponse([
            'data' => InvoiceResource::collection($projects),
            'total_records' => $projects->total(),
            'current_page' => $projects->currentPage(),
            'total_pages' => $projects->lastPage(),
            'page_num' => $page_mum,
            'per_page' => $per_page,
        ], ResponseMessage::OK, Response::HTTP_OK);
    }

    }

    public function getInvoice($invoice_id){

        if($invoice_id){
            $invoice = $this->invoiceRepository->find($invoice_id);
            return $this->successResponse(new InvoiceResource($invoice), ResponseMessage::OK, Response::HTTP_OK);
        }else{
            return $this->failureResponse('', 404);
        }

    }
    public function create(CreateInvoiceRequest $request)
    {
        $data = $request->prepareRequest();
        $invoice = $this->invoiceRepository->create($data);
        $invoice_id = $invoice->id;
        $project_id = $invoice->project_id ?? null;
        if($invoice){
            foreach($data['project_expenses'] as $exp){
                $this->projectExpenseRepository->create($exp , $invoice_id , $project_id);
            }
        }
        return $this->successResponse(new InvoiceResource($invoice), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function update(UpdateInvoiceRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $invoice = $this->invoiceRepository->update($id , $data);
        if($invoice){
            $this->projectExpenseRepository->updateOrCreate($data['project_expenses'] , $invoice->id , $invoice->project_id);
        }
        return $this->successResponse(new InvoiceResource($invoice), ResponseMessage::OK , Response::HTTP_OK);
    }
    public function destroy($id)
    {
        $this->invoiceRepository->delete($id);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);
    }
    public function generatePDF(InvoiceService $invoiceSerivce , $id){

        $invoice = $this->invoiceRepository->find($id);
        $prepare_data = new InvoicePdfResource($invoice);
        $invoice_pdf= $invoiceSerivce->generatePaymentPdf($prepare_data);
        return $this->successResponse($invoice_pdf, ResponseMessage::OK , Response::HTTP_OK);
     }

}
