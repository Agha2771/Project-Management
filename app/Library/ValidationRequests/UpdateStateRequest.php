<?php

namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class UpdateStateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Change this if authorization logic is needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $stateId = $this->route('state');

        return [
            'name' => 'required|string|max:255|unique:states,name,' . $stateId,
            'country_id' => 'required|exists:countries,id',
        ];
    }

    public function prepareRequest()
    {
        return [
            'name' => $this->input('name'),
            'country_id' => $this->input('country_id'),
        ];
    }
}
