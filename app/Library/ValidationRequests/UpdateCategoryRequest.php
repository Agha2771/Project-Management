<?php
namespace ProjectManagement\ValidationRequests;
use ProjectManagement\Abstracts\FormRequest;


class UpdateCategoryRequest extends FormRequest
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
        return [
           'name' => 'required|string|max:255|unique:countries,name,' . $this->route('id'), // Allowing the same name if it's unchanged
        ];
    }

    public function prepareRequest(){
        $request = $this;

        return [
            'name' => $request['name']
        ];
    }
}
