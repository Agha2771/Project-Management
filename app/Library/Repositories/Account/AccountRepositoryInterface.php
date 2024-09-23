<?php namespace ProjectManagement\Repositories\Account;

use ProjectManagement\Abstracts\RepositoryInterface;

interface AccountRepositoryInterface extends RepositoryInterface
{
    public function fetch_all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
