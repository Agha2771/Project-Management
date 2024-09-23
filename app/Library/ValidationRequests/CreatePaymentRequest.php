<?php
namespace ProjectManagement\ValidationRequests;
use ProjectManagement\Abstracts\FormRequest;


class CreatePaymentRequest extends FormRequest
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
            'invoice_id' => 'required|exists:invoices,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function prepareRequest()
{
    $request = $this;
    return [
        'invoice_id' => $request->input('invoice_id'),
        'amount_paid' => $request->input('amount_paid'),
        'payment_date' => $request->input('payment_date'),
        'description' => $request->input('description'),
    ];
}
}

