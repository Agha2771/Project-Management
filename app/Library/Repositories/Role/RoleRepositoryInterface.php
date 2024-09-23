<?php namespace ProjectManagement\Repositories\Role;

use ProjectManagement\Abstracts\RepositoryInterface;

interface RoleRepositoryInterface extends RepositoryInterface
{
    public function fetch_all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function syncPermissions($roleId, array $permissions);
    public function getAllPermissions();
}
