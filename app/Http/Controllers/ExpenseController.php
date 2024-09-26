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
            $states = $this->expenseRepository->fetch_all(null);
            return $this->successResponse(ExpenseResource::collection($states), ResponseMessage::OK, Response::HTTP_OK);
        }else if(!$request->has('page_num') && isset($country_id)){
            $states = $this->expenseRepository->fetch_all($country_id);
            return $this->successResponse(ExpenseResource::collection($states), ResponseMessage::OK, Response::HTTP_OK);
        }
        else{
            $per_page = $request->input('page_size', 10);
            $page_mum = $request->input('page_num', 1);
            $search = $request->input('search', '');
            $states = $this->expenseRepository->paginate($per_page, ['*'], 'page', $page_mum, $search);
            return $this->successResponse([
                'data' => ExpenseResource::collection($states),
                'total_records' => $states->total(),
                'current_page' => $states->currentPage(),
                'total_pages' => $states->lastPage(),
                'page_num' => $page_mum,
                'per_page' => $per_page,
            ], ResponseMessage::OK, Response::HTTP_OK);
        }

    }
    public function create(CreateExpenseRequest $request)
    {
        $data = $request->prepareRequest();
        $state = $this->expenseRepository->create($data);
        return $this->successResponse(new ExpenseResource($state), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function update(UpdateExpenseRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $state = $this->expenseRepository->update($id, $data);
        return $this->successResponse(new ExpenseResource($state), ResponseMessage::OK, Response::HTTP_OK);
    }
    public function destroy($id)
    {
        $state = $this->expenseRepository->find($id);
        if ($state) {
            $this->expenseRepository->delete($id);
        } else {
            return $this->failureResponse('Country not found!', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', ResponseMessage::OK, Response::HTTP_OK);
    }
}
