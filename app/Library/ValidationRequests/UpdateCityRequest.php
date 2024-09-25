<?php

namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class UpdateCityRequest extends FormRequest
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
        $cityId = $this->route('city');

        return [
            'name' => 'required|string|max:255|unique:states,name,' . $cityId,
            'state_id' => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
        ];
    }

    public function prepareRequest()
    {
        return [
            'name' => $this->input('name'),
            'state_id' => $this->input('state_id'),
            'country_id' => $this->input('country_id'),
        ];
    }
}
