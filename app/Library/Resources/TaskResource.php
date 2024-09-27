<?php
namespace ProjectManagement\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start_date' => $this->start_date,
            'end_date' => $this->due_date,
            'status' => $this->status,
            'description' => $this->description,
            'estimated_time' => $this->estimated_time,
            'project_id' => $this->project_id,
            'attachments' => $this->attachments,
            'project' => [
                'id' => $this->project->id,
                'title' => $this->project->title
            ],
            'user_id' => $this->user_id,
            'assignees' => $this->assignees->map(function ($assignee) {
                return [
                    'id' => $assignee->id,
                    'name' => $assignee->name,
                    'email' => $assignee->email,
                ];
            }),
            'assignee_ids' => $this->assignees->pluck('id')
        ];
    }
}
