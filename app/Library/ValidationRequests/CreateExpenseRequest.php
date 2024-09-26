<?php
namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class CreateExpenseRequest extends FormRequest
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
            'description' => 'required|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'amount' => 'required|numeric',
            'qty' => 'required|integer'
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

        return [
            'description' => $request->get('description'),
            'category_id' => $request->get('category_id'),
            'subcategory_id' => $request->get('subcategory_id'),
            'amount' => $request->get('amount'),
            'qty' => $request->get('qty'),
            'attachments' => $request->get('attachments')
        ];
    }
}
