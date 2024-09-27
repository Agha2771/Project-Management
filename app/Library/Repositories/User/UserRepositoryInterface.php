<?php namespace ProjectManagement\Repositories\User;

use ProjectManagement\Abstracts\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function fetch_all_users($type);

    public function create($data);
    public function find($id);

    public function update($data,$id);

    public function getByEmail($email);

    public function resetPassword($data);

    public function getUserWithSameRole($name);
    public function delete($id);
    public function paginate(int $perPage = 15, array $columns = ['*'], $pageName = 'page', $page = null, $searchTerm = null);

}
