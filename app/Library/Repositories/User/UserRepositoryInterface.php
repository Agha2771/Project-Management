<?php namespace ProjectManagement\Repositories\User;

use ProjectManagement\Abstracts\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function fetch_all_users($type);

    public function create($data);

    public function update($data,$id);

    public function getByEmail($email);

    public function resetPassword($data);

    public function getUserWithSameRole($name);
}
