<?php

namespace App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\State\StateRepositoryInterface;
use ProjectManagement\Resources\StateResource;
use ProjectManagement\ValidationRequests\CreateStateRequest;
use ProjectManagement\ValidationRequests\UpdateStateRequest;
use Illuminate\Http\Request;

class StateController extends Controller
{
    protected $stateRepository;
    use ApiResponseTrait;

    public function __construct(StateRepositoryInterface $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    public function index(Request $request ,  $country_id = null)
    {
        if (!$request->has('page_num') && !isset( $country_id)) {
            $states = $this->stateRepository->fetch_all(null);
            return $this->successResponse(StateResource::collection($states), ResponseMessage::OK, Response::HTTP_OK);
        }else if(!$request->has('page_num') && isset($country_id)){
            $states = $this->stateRepository->fetch_all($country_id);
            return $this->successResponse(StateResource::collection($states), ResponseMessage::OK, Response::HTTP_OK);
        }
        else{
            $per_page = $request->input('page_size', 10);
            $page_mum = $request->input('page_num', 1);
            $search = $request->input('search', '');
            $states = $this->stateRepository->paginate($per_page, ['*'], 'page', $page_mum, $search);
            return $this->successResponse([
                'data' => StateResource::collection($states),
                'total_records' => $states->total(),
                'current_page' => $states->currentPage(),
                'total_pages' => $states->lastPage(),
                'page_num' => $page_mum,
                'per_page' => $per_page,
            ], ResponseMessage::OK, Response::HTTP_OK);
        }

    }
    public function create(CreateStateRequest $request)
    {
        $data = $request->prepareRequest();
        $state = $this->stateRepository->create($data);
        return $this->successResponse(new StateResource($state), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function update(UpdateStateRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $state = $this->stateRepository->update($id, $data);
        return $this->successResponse(new StateResource($state), ResponseMessage::OK, Response::HTTP_OK);
    }
    public function destroy($id)
    {
        $state = $this->stateRepository->find($id);
        if ($state) {
            $this->stateRepository->delete($id);
        } else {
            return $this->failureResponse('Country not found!', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', ResponseMessage::OK, Response::HTTP_OK);
    }
}
