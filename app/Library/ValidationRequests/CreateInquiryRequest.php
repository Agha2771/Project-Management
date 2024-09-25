<?php namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class CreateInquiryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string|in:inquiry,in_progress,completed,on_hold,declined',
            'budget' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'currency_id' => 'required|exists:currencies,id',
            'client_id' => 'required|exists:users,id',
            'attachments' => 'nullable|array'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'start_date.date' => 'The start date is not a valid date.',
            'end_date.date' => 'The end date is not a valid date.',
            'end_date.after_or_equal' => 'The end date must be a date after or equal to the start date.',
            'status.in' => 'The status must be one of the following values: inquiry, not_started, in_progress, completed, on_hold, declined.',
            'budget.numeric' => 'The budget must be a number.',
            'budget.min' => 'The budget must be at least 0.',
            'description.string' => 'The description must be a string.',
            'currency_id.required' => 'The currency is required.',
            'currency_id.exists' => 'The selected currency is invalid.',
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'title' => $request->input('title'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status', 'not_started'),
            'budget' => $request->input('budget'),
            'description' => $request->input('description'),
            'currency_id' => $request->input('currency_id'),
            'client_id' => $request->input('client_id'),
            'attachments' => $request->input('attachments'),
        ];
    }
}
