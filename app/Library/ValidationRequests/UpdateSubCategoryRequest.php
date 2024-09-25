<?php

namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class UpdateSubCategoryRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function prepareRequest()
    {
        return [
            'name' => $this->input('name'),
            'category_id' => $this->input('category_id'),
        ];
    }
}
