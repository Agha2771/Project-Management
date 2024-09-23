<?php namespace ProjectManagement\ValidationRequests;


use ProjectManagement\Abstracts\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required',
        ];

    }
}
