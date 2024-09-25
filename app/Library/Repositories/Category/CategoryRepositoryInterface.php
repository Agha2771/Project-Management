<?php namespace ProjectManagement\Repositories\Category;

use ProjectManagement\Abstracts\RepositoryInterface;

interface CategoryRepositoryInterface extends RepositoryInterface
{

    public function fetch_all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
    public function paginate(int $per_age = 15, array $columns = ['*'], $page_name = 'page', $page = null, $search_term = null);

}
