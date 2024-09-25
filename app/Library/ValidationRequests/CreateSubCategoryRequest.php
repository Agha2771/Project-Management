<?php
namespace ProjectManagement\ValidationRequests;
use ProjectManagement\Abstracts\FormRequest;


class CreateSubCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:countries,name',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function prepareRequest(){
        $request = $this;

        return [
            'name' => $request['name'],
            'category_id' => $request['category_id'],
        ];
    }
}
