<?php
namespace ProjectManagement\ValidationRequests;
use ProjectManagement\Abstracts\FormRequest;


class CreateStateRequest extends FormRequest
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
            'country_id' => 'required|exists:countries,id',
        ];
    }

    public function prepareRequest(){
        $request = $this;

        return [
            'name' => $request['name'],
            'country_id' => $request['country_id'],
        ];
    }
}
