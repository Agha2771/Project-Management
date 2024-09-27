<?php namespace ProjectManagement\Repositories\TaskAssignees;

use ProjectManagement\Abstracts\RepositoryInterface;

interface TaskAssigneesRepositoryInterface extends RepositoryInterface
{
    public function create($id ,$data);
}
