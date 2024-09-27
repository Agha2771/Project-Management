<?php namespace ProjectManagement\Repositories\TaskAssignees;

use ProjectManagement\Models\TaskAssignee;
use ProjectManagement\Abstracts\EloquentRepository;

class TaskAssigneesEloquentRepository extends EloquentRepository implements TaskAssigneesRepositoryInterface
{
    public function __construct()
    {
        $this->model = new TaskAssignee();
    }

    public function find($task_id = null, $user_id = null)
    {
        return $this->model
                    ->where('task_id', $task_id)
                    ->orWhere('user_id', $user_id)
                    ->first();
    }

    public function create($id, $user_ids)
    {
        $this->model::where('task_id', $id)
            ->whereNotIn('user_id', $user_ids)
            ->delete();

        $tasks = [];

        $existingUserIds = $this->model::where('task_id', $id)
            ->pluck('user_id')
            ->toArray();

        foreach ($user_ids as $user_id) {
            if (!in_array($user_id, $existingUserIds)) {
                $task = new $this->model();
                $task->task_id = $id;
                $task->user_id = $user_id;
                $task->save();
                $tasks[] = $task;
            }
        }
        return $tasks;
    }

}
