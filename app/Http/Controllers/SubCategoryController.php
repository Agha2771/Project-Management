<?php

namespace App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\SubCategory\SubCategoryRepositoryInterface;
use ProjectManagement\Resources\SubCategoryResource;
use ProjectManagement\ValidationRequests\CreateSubCategoryRequest;
use ProjectManagement\ValidationRequests\UpdateSubCategoryRequest;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    protected $subCategoryRepository;
    use ApiResponseTrait;

    public function __construct(SubCategoryRepositoryInterface $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function index(Request $request ,  $category_id = null)
    {
        if (!$request->has('page_num') && !isset( $category_id)) {
            $states = $this->subCategoryRepository->fetch_all(null);
            return $this->successResponse(SubCategoryResource::collection($states), ResponseMessage::OK, Response::HTTP_OK);
        }else if(!$request->has('page_num') && isset($category_id)){
            $states = $this->subCategoryRepository->fetch_all($category_id);
            return $this->successResponse(SubCategoryResource::collection($states), ResponseMessage::OK, Response::HTTP_OK);
        }
        else{
            $per_page = $request->input('page_size', 10);
            $page_mum = $request->input('page_num', 1);
            $search = $request->input('search', '');
            $states = $this->subCategoryRepository->paginate($per_page, ['*'], 'page', $page_mum, $search);
            return $this->successResponse([
                'data' => SubCategoryResource::collection($states),
                'total_records' => $states->total(),
                'current_page' => $states->currentPage(),
                'total_pages' => $states->lastPage(),
                'page_num' => $page_mum,
                'per_page' => $per_page,
            ], ResponseMessage::OK, Response::HTTP_OK);
        }

    }
    public function create(CreateSubCategoryRequest $request)
    {
        $data = $request->prepareRequest();
        $state = $this->subCategoryRepository->create($data);
        return $this->successResponse(new SubCategoryResource($state), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function update(UpdateSubCategoryRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $state = $this->subCategoryRepository->update($id, $data);
        return $this->successResponse(new SubCategoryResource($state), ResponseMessage::OK, Response::HTTP_OK);
    }
    public function destroy($id)
    {
        $state = $this->subCategoryRepository->find($id);
        if ($state) {
            $this->subCategoryRepository->delete($id);
        } else {
            return $this->failureResponse('Country not found!', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', ResponseMessage::OK, Response::HTTP_OK);
    }
}
