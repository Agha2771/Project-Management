<?php namespace ProjectManagement\ValidationRequests;


use ProjectManagement\Abstracts\FormRequest;

class PasswordResetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'password' => 'required|confirmed|min:8',
            'resetToken' => 'required',
        ];

    }

    public function prepareRequest() {
        $request=$this;
        return [
            'resetToken' => $request['resetToken'],
            'password' => $request['password'],
        ];

    }
}
