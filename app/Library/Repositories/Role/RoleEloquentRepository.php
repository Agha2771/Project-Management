<?php namespace ProjectManagement\Repositories\Role;


use ProjectManagement\Abstracts\EloquentRepository;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleEloquentRepository extends EloquentRepository implements RoleRepositoryInterface
{
  public function __construct()
  {
    $this->model = new Role();
  }
  public function fetch_all()
  {
      $roles =  $this->model->all();
      foreach ($roles as $role)
      {
        $role['permissions'] = $role->permissions;
      }

      return $roles;
  }

  public function find($id)
  {
      return $this->model->where('id' ,$id)->first();
  }

  public function create($data){
    $role = new $this->model();
    $role->name = $data['name'];
    $role->guard_name ='api';
    $role->save();
    return $role;
}

public function update($id,$data){
    $role = $this->find($id);
    if(isset($data['name'])){
        $role->name = $data['name'];
    }

    if (isset($data['permissions']) && is_array($data['permissions']) && count($data['permissions']) > 0) {
        $this->syncPermissions($id, $data['permissions']);
    }

    $role->save();
    return $role;
}
  public function delete($id)
  {
      $role = $this->find($id);
      $role->delete();
      
  }

  public function syncPermissions($roleId, array $permissions)
  {
      $role = $this->find($roleId);
      $role->syncPermissions($permissions);
  }
  
  public function getAllPermissions()
  {
      return Permission::all();
  }
}
