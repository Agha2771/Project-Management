<?php namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'sometimes|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_date' => 'sometimes|date',
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,sent',
            'project_expenses' => 'nullable|array',
            'currency_id'=> 'sometimes|exists:currencies,id'
        ];
    }
    /**
     * Return the validated and processed data for invoice update.
     */
    public function prepareRequest()
    {
        $request = $this;
        return [
            'project_id' => $request->input('project_id'),
            'user_id' => $request->input('user_id'),
            'invoice_date' => $request->input('invoice_date'),
            'due_date' => $request->input('due_date'),
            'amount' => $request->input('amount'),
            'status' => $request->input('status'),
            'currency_id' => $request->input('currency_id'),
            'project_expenses' => $request->input('project_expenses'),
        ];
    }
}
