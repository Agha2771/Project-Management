<?php

namespace App\Http\Controllers;

use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Project\ProjectRepositoryInterface;
use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Resources\ProjectResource;
use ProjectManagement\Resources\ProjectWithExpensesResource;

use ProjectManagement\ValidationRequests\CreateProjectRequest;
use ProjectManagement\ValidationRequests\UpdateProjectRequest;
use ProjectManagement\ValidationRequests\CreateProductAttachmentRequest;
use App\Helpers\helper;
use ProjectManagement\Repositories\Client\ClientRepositoryInterface;
use ProjectManagement\Repositories\ProjectAssignees\ProjectAssigneesRepositoryInterface;
use ProjectManagement\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectRepository;
    protected $clientRepository;
    protected $proAssigneesRepository;
    use ApiResponseTrait;

    public function __construct(ProjectRepositoryInterface $projectRepository , ProjectAssigneesRepositoryInterface $proAssigneesRepository , ClientRepositoryInterface $clientRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->proAssigneesRepository = $proAssigneesRepository;
        $this->clientRepository = $clientRepository;
    }

    public function index($client_id)
    {
        // $client = $this->clientRepository->find($client_id);
        $projects = $this->projectRepository->fetch_all($client_id);
        return $this->successResponse(ProjectWithExpensesResource::collection($projects), ResponseMessage::OK , Response::HTTP_OK);
    }
    public function getProjects(Request $request)
    {
        $perPage = $request->input('page_size', 10);
        $pageNum = $request->input('page_num', 1);
        $search = $request->input('search', '');
        $projects = $this->projectRepository->paginate($perPage, ['*'], 'page', $pageNum, $search);
        return $this->successResponse([
            'data' => ProjectResource::collection($projects),
            'total_records' => $projects->total(),
            'current_page' => $projects->currentPage(),
            'total_pages' => $projects->lastPage(),
            'page_num' => $pageNum,
            'per_page' => $perPage,
        ], ResponseMessage::OK, Response::HTTP_OK);
    }
    public function create(CreateProjectRequest $request)
    {
        $data = $request->prepareRequest();
        $project = $this->projectRepository->create($data);
        $this->proAssigneesRepository->create($project->id , $data['assignee_ids']);
        return $this->successResponse(new ProjectResource($project), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $project = $this->projectRepository->update($id , $data);
        $this->proAssigneesRepository->create($id , $data['assignees']);
        return $this->successResponse(new ProjectResource($project), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function storeAttachments(CreateProductAttachmentRequest $request)
    {
        $validated = $request->validatedWithFilePaths();
        helper::storeAttachments($validated);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->projectRepository->delete($id);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);
    }
}
