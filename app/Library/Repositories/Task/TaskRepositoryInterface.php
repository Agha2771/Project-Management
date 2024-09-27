<?php namespace ProjectManagement\Repositories\Task;

use ProjectManagement\Abstracts\RepositoryInterface;

interface TaskRepositoryInterface extends RepositoryInterface
{
    public function fetch_all($client_id);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function paginate(int $perPage = 15, array $columns = ['*'], $pageName = 'page', $page = null, $searchTerm = null);

}
