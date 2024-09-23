<?php
namespace ProjectManagement\ValidationRequests;
use ProjectManagement\Abstracts\FormRequest;


class UpdatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Adjust according to your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount_paid' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|nullable|string|max:255',
            'payment_date' => 'sometimes|date',

        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'amount_paid' => $request->input('amount_paid'),
            'payment_date' => $request->input('payment_date'),
            'description' => $request->input('description'),
        ];
    }
}
