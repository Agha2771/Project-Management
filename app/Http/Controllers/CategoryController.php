<?php

namespace App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Category\CategoryRepositoryInterface;
use ProjectManagement\Resources\CategoryResource;
use Illuminate\Http\Request;
use ProjectManagement\ValidationRequests\CreateCategoryRequest;
use ProjectManagement\ValidationRequests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    protected $categoryRepository;
    use ApiResponseTrait;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        if (!$request->has('page_num')) {
            $countries = $this->categoryRepository->fetch_all();
            return $this->successResponse(CategoryResource::collection($countries), ResponseMessage::OK, Response::HTTP_OK);
        }else{
            $per_page = $request->input('page_size', 10);
            $page_mum = $request->input('page_num', 1);
            $search = $request->input('search', '');
            $countries = $this->categoryRepository->paginate($per_page, ['*'], 'page', $page_mum, $search);
            return $this->successResponse([
                'data' => CategoryResource::collection($countries),
                'total_records' => $countries->total(),
                'current_page' => $countries->currentPage(),
                'total_pages' => $countries->lastPage(),
                'page_num' => $page_mum,
                'per_page' => $per_page,
            ], ResponseMessage::OK, Response::HTTP_OK);
        }

    }
    public function create(CreateCategoryRequest $request)
    {
        $data = $request->prepareRequest();
        $category = $this->categoryRepository->create($data);
        return $this->successResponse(new CategoryResource($category), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $category = $this->categoryRepository->update($id, $data);
        return $this->successResponse(new CategoryResource($category), ResponseMessage::OK, Response::HTTP_OK);
    }
    public function destroy($id)
    {
        $category = $this->categoryRepository->find($id);
        if ($category) {
            $this->categoryRepository->delete($id);
        } else {
            return $this->failureResponse('Category not found!', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', ResponseMessage::OK, Response::HTTP_OK);
    }
}
