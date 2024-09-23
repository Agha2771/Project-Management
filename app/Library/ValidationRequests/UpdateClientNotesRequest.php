<?php namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class UpdateClientNotesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'inquiry_id' => 'nullable|exists:inquiries,id',
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'inquiry_id' => $request->input('inquiry_id'),
        ];
    }
}
