<?php namespace ProjectManagement\Repositories\ProjectAssignees;

use ProjectManagement\Models\ProjectAssignees;
use ProjectManagement\Abstracts\EloquentRepository;

class ProjectAssigneesEloquentRepository extends EloquentRepository implements ProjectAssigneesRepositoryInterface
{
    public function __construct()
    {
        $this->model = new ProjectAssignees();
    }

    public function find($project_id, $user_id)
    {
        return $this->model
                    ->where('project_id', $project_id)
                    ->where('user_id', $user_id)
                    ->first();
    }

    public function create($id, $user_ids)
    {
        $this->model::where('project_id', $id)
            ->whereNotIn('user_id', $user_ids)
            ->delete();

        $projects = [];

        $existingUserIds = $this->model::where('project_id', $id)
            ->pluck('user_id')
            ->toArray();

        foreach ($user_ids as $user_id) {
            if (!in_array($user_id, $existingUserIds)) {
                $project = new $this->model();
                $project->project_id = $id;
                $project->user_id = $user_id;
                $project->save();
                $projects[] = $project;
            }
        }
        return $projects;
    }

}
