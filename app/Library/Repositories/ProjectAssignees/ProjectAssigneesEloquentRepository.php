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
  
    public function create($id ,  $user_ids)
    {
        $projects = [];
        foreach ($user_ids as $user_id) {
            $already_exists = $this->find($id, $user_id);
            if (!$already_exists) {
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
