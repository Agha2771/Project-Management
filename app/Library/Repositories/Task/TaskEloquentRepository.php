<?php namespace ProjectManagement\Repositories\Task;

use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Enums\ProjectStatuses;
use ProjectManagement\Models\Task;

class TaskEloquentRepository extends EloquentRepository implements TaskRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Task();
    }

    public function fetch_all($task_id)
    {
        if($task_id){
            $tasks =  $this->model->where('project_id' , $task_id)->get();

        }else{
            $tasks =  $this->model->all();
        }
        return $tasks;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        $task = new $this->model();
        $task->user_id =auth()->user()->id;
        $task->title = $data['title'];
        $task->start_date = $data['start_date'] ?? null;
        $task->due_date = $data['end_date'] ?? null;
        $task->status = $data['status'] ?? 'todo';
        $task->estimated_time = $data['estimated_time'] ?? null;
        $task->description = $data['description'] ?? null;
        $task->project_id = $data['project_id'] ?? null;
        $task->save();
        return $task;
    }

    public function update($id, $data)
    {
        $task = $this->find($id);
        if ($task) {
            $task->title = $data['title'] ?? $task->title;
            $task->start_date = $data['start_date'] ?? $task->start_date;
            $task->due_date = $data['end_date'] ?? $task->due_date;
            $task->status = $data['status'] ?? $task->status;
            $task->estimated_time = $data['estimated_time'] ?? $task->estimated_time;
            $task->description = $data['description'] ?? $task->description;
            $task->project_id = $data['project_id'] ?? $task->project_id;
            $task->save();
        }
        return $task;
    }

    public function delete($id)
    {
        $task = $this->find($id);
        if ($task) {
            $task->delete();
        }
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], $pageName = 'page', $page = null, $searchTerm = null)
    {
        $query = $this->model::with('assignees');

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('status', 'like', "%{$searchTerm}%")
                      ->orWhereHas('assignees', function ($query) use ($searchTerm) {
                          $query->where('name', 'like', "%{$searchTerm}%");
                      });
            });
        }
        $query->orderBy('created_at', 'desc');
        return $query->paginate($perPage, $columns, $pageName, $page);
    }
}
