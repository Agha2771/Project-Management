<?php namespace ProjectManagement\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'permissions' => $this->permissions,
            'permission_ids' =>  $this->permissions() ? $this->permissions()->pluck('id'): [],
            'user_count' => $this->users()->count()
        ];
    }
}
