<?php namespace ProjectManagement\Repositories\ProjectExpense;

use ProjectManagement\Abstracts\RepositoryInterface;

interface ProjectExpenseRepositoryInterface extends RepositoryInterface
{
    public function create(array $data,$invoice_id , $project_id);
    public function updateOrCreate($data,$invoice_id, $project_id);
}
