<?php

namespace App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\City\CityRepositoryInterface;
use ProjectManagement\Resources\CityResource;
use ProjectManagement\ValidationRequests\CreateCityRequest;
use ProjectManagement\ValidationRequests\UpdateCityRequest;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected $cityRepository;
    use ApiResponseTrait;


    public function index(Request $request ,  $state_id = null)
    {
        if (!$request->has('page_num') && !isset( $state_id)) {
            $states = $this->cityRepository->fetch_all(null);
            return $this->successResponse(CityResource::collection($states), ResponseMessage::OK, Response::HTTP_OK);
        }else if(!$request->has('page_num') && isset($state_id)){
            $states = $this->cityRepository->fetch_all($state_id);
            return $this->successResponse(CityResource::collection($states), ResponseMessage::OK, Response::HTTP_OK);
        }
        else{
            $per_page = $request->input('page_size', 10);
            $page_mum = $request->input('page_num', 1);
            $search = $request->input('search', '');
            $states = $this->cityRepository->paginate($per_page, ['*'], 'page', $page_mum, $search);
            return $this->successResponse([
                'data' => CityResource::collection($states),
                'total_records' => $states->total(),
                'current_page' => $states->currentPage(),
                'total_pages' => $states->lastPage(),
                'page_num' => $page_mum,
                'per_page' => $per_page,
            ], ResponseMessage::OK, Response::HTTP_OK);
        }

    }
    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }
    public function create(CreateCityRequest $request)
    {
        $data = $request->prepareRequest();
        $city = $this->cityRepository->create($data);
        return $this->successResponse(new CityResource($city), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function update(UpdateCityRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $city = $this->cityRepository->update($id, $data);
        return $this->successResponse(new CityResource($city), ResponseMessage::OK, Response::HTTP_OK);
    }
    public function destroy($id)
    {
        $city = $this->cityRepository->find($id);
        if ($city) {
            $this->cityRepository->delete($id);
        } else {
            return $this->failureResponse('Country not found!', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', ResponseMessage::OK, Response::HTTP_OK);
    }
}
