<?php namespace ProjectManagement\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ] : null,
            'lead' => $this->lead ? [
                'id' => $this->lead->id,
                'title' => $this->lead->title,
            ] : null,
            'assignees' => ProjectAssigneeResource::collection($this->assignees),
            'user_id' => $this->user_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'budget' => $this->budget,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'currency' => new CurrencyResource($this->currency),
            'attachments' => $this->attachments
        ];
    }
}
