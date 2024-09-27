<?php namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:todo,in_progress,completed,paused,bus_fixes,qa',
            'description' => 'nullable|string',
            'estimated_time' => ['nullable', 'regex:/^(?:(\d+)h)? ?(?:(\d+)m)?$/'],
            'project_id' => 'sometimes|exists:projects,id',
            'assignee_ids' => 'nullable|array',
        ];
    }

    public function messages()
    {
        return [
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',

            'start_date.date' => 'The start date must be a valid date.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be a date after or equal to the start date.',

            'status.string' => 'The status must be a string.',
            'status.in' => 'The status must be one of the following values: not_started, in_progress, completed, on_hold, declined.',

            'description.string' => 'The description must be a string.',

            'estimated_time.regex' => 'The estimated time must be in the format "8h" or "8h 30m".',

            'project_id.exists' => 'The selected project is invalid.',
            'user_id.exists' => 'The selected user is invalid.',

            'assignee_ids.array' => 'The assignees must be an array.',
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'title' => $request->input('title'),
            'inquiry_id' => $request->input('project_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status'),
            'description' => $request->input('description'),
            'estimated_time' => $request->input('estimated_time'),
            'assignee_ids' => $request->input('assignee_ids'),
            'attachments' => $request->input('attachments'),
        ];
    }
}
