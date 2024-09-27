<?php

namespace App\Http\Controllers;

use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Resources\TaskResource;
use ProjectManagement\Resources\ProjectWithExpensesResource;
use ProjectManagement\ValidationRequests\CreateTaskRequest;
use ProjectManagement\ValidationRequests\UpdateTaskRequest;
use App\Helpers\helper;
use ProjectManagement\Models\ProjectAttachment;
use Illuminate\Http\Request;
use ProjectManagement\Repositories\Task\TaskRepositoryInterface;
use ProjectManagement\Repositories\TaskAssignees\TaskAssigneesRepositoryInterface;

class TaskController extends Controller
{
    protected $taskRepository;
    protected $taskAssigneeRepository;

    use ApiResponseTrait;

    public function __construct(TaskRepositoryInterface $taskRepository , TaskAssigneesRepositoryInterface $taskAssigneeRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->taskAssigneeRepository = $taskAssigneeRepository;
    }

    public function index($project_id)
    {
        $tasks = $this->taskRepository->fetch_all($project_id);
        return $this->successResponse(TaskResource::collection($tasks), ResponseMessage::OK , Response::HTTP_OK);
    }
    public function getTasks(Request $request)
    {
        $perPage = $request->input('page_size', 10);
        $pageNum = $request->input('page_num', 1);
        $search = $request->input('search', '');
        $tasks = $this->taskRepository->paginate($perPage, ['*'], 'page', $pageNum, $search);
        return $this->successResponse([
            'data' => TaskResource::collection($tasks),
            'total_records' => $tasks->total(),
            'current_page' => $tasks->currentPage(),
            'total_pages' => $tasks->lastPage(),
            'page_num' => $pageNum,
            'per_page' => $perPage,
        ], ResponseMessage::OK, Response::HTTP_OK);
    }
    public function create(CreateTaskRequest $request)
    {
        $data = $request->prepareRequest();
        $task = $this->taskRepository->create($data);
        if (!empty($data['assignee_ids'])) {
            $this->taskAssigneeRepository->create($task->id, $data['assignee_ids']);
        }
        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            $prepare_data = [
                'item_type' => 'task',
                'item_id' => $task->id,
                'files' => $attachments
            ];
            helper::storeAttachments($prepare_data);
        }
        return $this->successResponse(new TaskResource($task), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $task = $this->taskRepository->update($id , $data);
        if (!empty($data['assignee_ids'])) {
            $this->taskAssigneeRepository->create($task->id, $data['assignee_ids']);
        }
        return $this->successResponse(new TaskResource($task), ResponseMessage::OK , Response::HTTP_OK);
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
        $this->taskRepository->delete($id);
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
