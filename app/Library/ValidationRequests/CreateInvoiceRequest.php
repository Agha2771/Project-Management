<?php namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'in:pending,sent',
            'project_expenses' => 'required|array',
            'currency_id'=> 'required|exists:currencies,id',
        ];
    }

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


