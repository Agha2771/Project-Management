<?php

namespace  App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use ProjectManagement\Repositories\Account\AccountRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Resources\AccountResource;
use ProjectManagement\ValidationRequests\CreateAccountRequest;
use ProjectManagement\ValidationRequests\UpdateAccountRequest;

class AccountController extends Controller
{
    protected $accountRepository;
    use ApiResponseTrait;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function get_invoices($project_id)
    {
        $clients = $this->accountRepository->fetch_all();
        return $this->successResponse(AccountResource::collection($clients), ResponseMessage::OK , Response::HTTP_OK);
    }
    public function create(CreateAccountRequest $request)
    {
        $data = $request->prepareRequest();
        $client = $this->accountRepository->create($data);
        return $this->successResponse(new AccountResource($client), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function update(UpdateAccountRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $client = $this->accountRepository->update($id , $data);
        return $this->successResponse(new AccountResource($client), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->accountRepository->delete($id);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);
    }

}
