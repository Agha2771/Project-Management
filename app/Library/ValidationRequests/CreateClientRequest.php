<?php namespace ProjectManagement\ValidationRequests;


use ProjectManagement\Abstracts\FormRequest;

class CreateClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'business_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'referance' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'business_name.required' => 'The business name is required.',
            'contact_person.required' => 'The contact person is required.',
            'email.required' => 'The email address is required.',
            'email.unique' => 'The email address has already been taken.',
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'business_name' => $request['business_name'],
            'contact_person' => $request['contact_person'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'address' => $request['address'],
            'referance' => $request['referance']
        ];
    }
}
