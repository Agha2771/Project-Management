<?php
namespace ProjectManagement\ValidationRequests;
use ProjectManagement\Abstracts\FormRequest;


class CreateAccountRequest extends FormRequest
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
            'account_title' => [
                'required',
                'string',
                'in:credit_card,debit_card,jazz_cash,easy_paisa,cash',
            ],
        ];
    }

    public function prepareRequest(){
        $request = $this;

        return [
            'title' => $request['title']
        ];
    }
}
