<?php namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class CreateClientNotesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'inquiry_id' => 'required|exists:inquiries,id', // Add validation for inquiry_id
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'inquiry_id' => $request->input('inquiry_id'), // Add inquiry_id
        ];
    }
}
