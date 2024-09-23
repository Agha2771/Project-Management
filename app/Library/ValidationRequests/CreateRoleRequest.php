<?php namespace ProjectManagement\ValidationRequests;


use ProjectManagement\Abstracts\FormRequest;

class CreateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:roles,name'],
            'permissions' => ['array' , 'required']
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'name' => $request['name'],
            'permissions' => $request['permissions']
        ];
    }
}
