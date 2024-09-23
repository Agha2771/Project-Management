<?php

namespace  App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Client\ClientRepositoryInterface;
use ProjectManagement\Repositories\ClientNotes\ClientNotesRepositoryInterface;
use ProjectManagement\Repositories\User\UserRepositoryInterface;
use ProjectManagement\Resources\ClientResource;
use ProjectManagement\Resources\ClientWithProjectsResource;
use Illuminate\Http\Request;
use ProjectManagement\Models\Currency;
use ProjectManagement\Resources\ClientNotesResource;
use ProjectManagement\ValidationRequests\CreateClientRequest;
use ProjectManagement\ValidationRequests\UpdateClientRequest;
use ProjectManagement\ValidationRequests\CreateClientNotesRequest;
use ProjectManagement\ValidationRequests\UpdateClientNotesRequest;

class ClientController extends Controller
{
    protected $clientRepository;
    protected $clientNotesRepository;
    protected $userRepository;
    use ApiResponseTrait;

    public function __construct(ClientRepositoryInterface $clientRepository , UserRepositoryInterface $userRepository , ClientNotesRepositoryInterface $clientNotesRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
        $this->clientNotesRepository = $clientNotesRepository;
    }
    public function index(Request $request)
    {
        $perPage = $request->input('page_size', 10);
        $pageNum = $request->input('page_num', 1);
        $search = $request->input('search', '');
        $clients = $this->clientRepository->paginate($perPage, ['*'], 'page', $pageNum, $search);
        return $this->successResponse([
            'data' => ClientResource::collection($clients),
            'total_records' => $clients->total(),
            'current_page' => $clients->currentPage(),
            'total_pages' => $clients->lastPage(),
            'page_num' => $pageNum,
            'per_page' => $perPage,
        ], ResponseMessage::OK, Response::HTTP_OK);
    }

    public function currencies(){
        $currencies = Currency::select('id' , 'title')->get();
        return $this->successResponse($currencies ,  ResponseMessage::OK, Response::HTTP_OK);
    }

    public function getAllClients(Request $request)
    {
        $clients = $this->userRepository->fetch_all_clients();
        return $this->successResponse($clients, ResponseMessage::OK, Response::HTTP_OK);
    }
    public function getClient($client_id){
        $client = $this->clientRepository->find($client_id);
        if(!$client){
            $this->failureResponse('Client not found!' , 404);
        }
        return $this->successResponse(new ClientWithProjectsResource($client), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function create(CreateClientRequest $request)
    {
        $data = $request->prepareRequest();
        $user_data = [
            'name' => $data['business_name'],
            'email' => $data['email'],
            'password' => 12345678,
            'image' => null,
            'user_type' => 'client'
        ];
        $user = $this->userRepository->create($user_data);
        $data['user_id'] = $user->id;
        $client = $this->clientRepository->create($data);
        return $this->successResponse(new ClientResource($client), ResponseMessage::OK, Response::HTTP_OK);
    }


    public function update(UpdateClientRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $client = $this->clientRepository->update($id , $data);
        return $this->successResponse(new ClientResource($client), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $client = $this->clientRepository->find($id);
        $this->userRepository->delete($client->user_id);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);

    }


    public function getNotes()
    {
        $user_id = auth()->user()->id;
        $client_notes = $this->clientNotesRepository->fetch_all($user_id);
        return $this->successResponse(ClientNotesResource::collection($client_notes), ResponseMessage::OK , Response::HTTP_OK);
    }


    public function createNote(CreateClientNotesRequest $request)
    {
        $data = $request->prepareRequest();
        $client_note = $this->clientNotesRepository->create($data);
        return $this->successResponse(new ClientNotesResource($client_note), ResponseMessage::OK, Response::HTTP_OK);
    }


    public function updateNote(UpdateClientNotesRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $client = $this->clientNotesRepository->update($id , $data);
        return $this->successResponse(new ClientNotesResource($client), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function destroyNote($id)
    {
        $client = $this->clientNotesRepository->delete($id);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);

    }
}
