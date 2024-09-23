<?php namespace ProjectManagement\Repositories\Client;

use ProjectManagement\Abstracts\RepositoryInterface;

interface ClientRepositoryInterface extends RepositoryInterface
{
    public function fetch_all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function paginate(int $perPage = 15, array $columns = ['*'], $pageName = 'page', $page = null, $searchTerm = null);
}
