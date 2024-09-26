<?php
namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'description' => 'sometimes|string|max:1000',
            'category_id' => 'sometimes|exists:categories,id',
            'subcategory_id' => 'sometimes|exists:sub_categories,id',
            'amount' => 'sometimes|numeric',
            'qty' => 'sometimes|numeric'
        ];
    }

    /**
     * Prepare the request data for further processing.
     *
     * @return array
     */
    public function prepareRequest()
    {
        $request = $this;

        return array_filter([
            'description' => $request->get('description'),
            'category_id' => $request->get('category_id'),
            'subcategory_id' => $request->get('subcategory_id'),
            'amount' => $request->get('amount'),
            'qty' => $request->get('qty'),
        ]);
    }
}
