<?php

namespace App\Http\Controllers;

use ProjectManagement\Enums\ResponseMessage;
use ProjectManagement\Repositories\Inquiry\InquiryRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Project\ProjectRepositoryInterface;
use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Resources\ProjectResource;
use ProjectManagement\Resources\ProjectWithExpensesResource;
use ProjectManagement\Enums\InquiryStatuses;
use ProjectManagement\ValidationRequests\CreateProjectRequest;
use ProjectManagement\ValidationRequests\UpdateProjectRequest;
use ProjectManagement\ValidationRequests\CreateProductAttachmentRequest;
use App\Helpers\helper;
use ProjectManagement\Models\ProjectAttachment;
use ProjectManagement\Repositories\Client\ClientRepositoryInterface;
use ProjectManagement\Repositories\ProjectAssignees\ProjectAssigneesRepositoryInterface;
use ProjectManagement\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectRepository;
    protected $inquiryRepository;
    protected $clientRepository;
    protected $proAssigneesRepository;
    use ApiResponseTrait;

    public function __construct(ProjectRepositoryInterface $projectRepository , ProjectAssigneesRepositoryInterface $proAssigneesRepository , ClientRepositoryInterface $clientRepository , InquiryRepositoryInterface $inquiryRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->proAssigneesRepository = $proAssigneesRepository;
        $this->clientRepository = $clientRepository;
        $this->inquiryRepository = $inquiryRepository;
    }

    public function index($client_id, $type=null)
    {
        if($type == 'user'){
            $projects = $this->projectRepository->fetch_all($client_id);

        }else{
            $client = $this->clientRepository->find($client_id);
            $projects = $this->projectRepository->fetch_all($client->user_id);
        }
        return $this->successResponse(ProjectWithExpensesResource::collection($projects), ResponseMessage::OK , Response::HTTP_OK);
    }
    public function getProjects(Request $request)
    {
        if (!$request->has('page_num')){
            $invoices = $this->projectRepository->fetch_all(null);
            return $this->successResponse(ProjectResource::collection($invoices), ResponseMessage::OK , Response::HTTP_OK);
        }else{
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
    }
    public function create(CreateProjectRequest $request)
    {
        $data = $request->prepareRequest();
        $project = $this->projectRepository->create($data);
        if (!empty($data['assignee_ids'])) {
            $this->proAssigneesRepository->create($project->id, $data['assignee_ids']);
        }
        if ($project && isset($project->inquiry_id)) {
            $inquiry = $this->inquiryRepository->find($project->inquiry_id);
            if (!$inquiry){
                return $this->failureResponse('Inquiry not found!', Response::HTTP_NOT_FOUND);
            }
            helper::approveInquiry($inquiry->attachments , $project->id);
        }
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            $prepare_data = [
                'item_type' => 'project',
                'item_id' => $project->id,
                'files' => $attachments
            ];
            helper::storeAttachments($prepare_data);
        }
        return $this->successResponse(new ProjectResource($project), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $project = $this->projectRepository->update($id , $data);
        // $this->proAssigneesRepository->create($id , $data['assignees']);
        return $this->successResponse(new ProjectResource($project), ResponseMessage::OK , Response::HTTP_OK);
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
    public function destroy($id)
    {
        $this->projectRepository->delete($id);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);
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
