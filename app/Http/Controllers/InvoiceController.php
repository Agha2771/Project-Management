<?php

namespace  App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Invoice\InvoiceRepositoryInterface;
use ProjectManagement\Repositories\ProjectExpense\ProjectExpenseRepositoryInterface;
use ProjectManagement\Resources\InvoiceResource;
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
        $invoices = $this->invoiceRepository->fetch_all();
        return $this->successResponse(InvoiceResource::collection($invoices), ResponseMessage::OK , Response::HTTP_OK);
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
        return $this->successResponse(new InvoiceResource($invoice), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->invoiceRepository->delete($id);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);
    }

}
