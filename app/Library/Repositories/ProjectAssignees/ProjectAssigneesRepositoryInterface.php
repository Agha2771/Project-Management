<?php namespace ProjectManagement\Repositories\ProjectAssignees;

use ProjectManagement\Abstracts\RepositoryInterface;

interface ProjectAssigneesRepositoryInterface extends RepositoryInterface
{
    public function create($id ,$data);
}
