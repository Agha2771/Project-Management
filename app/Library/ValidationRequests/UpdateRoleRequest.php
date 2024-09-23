<?php namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $roleId = $this->route('roleId');
        
        return [
            'name' => [
                'required', 
                'string', 
                'unique:roles,name,' . $roleId 
            ],
            'permissions' => ['array'],
            'roleId' => ['exists:roles,id'] 
        ];
    }

    public function prepareRequest()
    {
        $request = $this;

        return [
            'name' => $request->get('name'),
            'permissions' => $request->get('permissions', [])
        ];
    }
}
