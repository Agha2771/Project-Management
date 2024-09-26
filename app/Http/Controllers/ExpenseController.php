<?php

namespace App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Expense\ExpenseRepositoryInterface;
use ProjectManagement\Resources\ExpenseResource;
use ProjectManagement\ValidationRequests\CreateExpenseRequest;
use ProjectManagement\ValidationRequests\UpdateExpenseRequest;
use Illuminate\Http\Request;
use app\Helpers\helper;
use ProjectManagement\Models\ProjectAttachment;
class ExpenseController extends Controller
{
    protected $expenseRepository;
    use ApiResponseTrait;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function index(Request $request ,  $country_id = null)
    {
        if (!$request->has('page_num') && !isset( $country_id)) {
            $expenses = $this->expenseRepository->fetch_all(null);
            return $this->successResponse(ExpenseResource::collection($expenses), ResponseMessage::OK, Response::HTTP_OK);
        }else if(!$request->has('page_num') && isset($country_id)){
            $expenses = $this->expenseRepository->fetch_all($country_id);
            return $this->successResponse(ExpenseResource::collection($expenses), ResponseMessage::OK, Response::HTTP_OK);
        }
        else{
            $per_page = $request->input('page_size', 10);
            $page_mum = $request->input('page_num', 1);
            $search = $request->input('search', '');
            $expenses = $this->expenseRepository->paginate($per_page, ['*'], 'page', $page_mum, $search);
            return $this->successResponse([
                'data' => ExpenseResource::collection($expenses),
                'total_records' => $expenses->total(),
                'current_page' => $expenses->currentPage(),
                'total_pages' => $expenses->lastPage(),
                'page_num' => $page_mum,
                'per_page' => $per_page,
            ], ResponseMessage::OK, Response::HTTP_OK);
        }

    }
    public function create(CreateExpenseRequest $request)
    {
        $data = $request->prepareRequest();
        $expense = $this->expenseRepository->create($data);
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            $prepare_data = [
                'item_type' => 'expense',
                'item_id' => $expense->id,
                'files' => $attachments
            ];
            helper::storeAttachments($prepare_data);
        }
        return $this->successResponse(new ExpenseResource($expense), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function update(UpdateExpenseRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $expense = $this->expenseRepository->update($id, $data);
        return $this->successResponse(new ExpenseResource($expense), ResponseMessage::OK, Response::HTTP_OK);
    }
    public function destroy($id)
    {
        $expense = $this->expenseRepository->find($id);
        if ($expense) {
            $this->expenseRepository->delete($id);
        } else {
            return $this->failureResponse('Country not found!', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', ResponseMessage::OK, Response::HTTP_OK);
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
