<?php namespace ProjectManagement\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientWithProjectsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'business_name' => $this->business_name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'country' => $this->country ? $this->country->name : null,
            'state' => $this->state ? $this->state->name : null,
            'city' => $this->city ? $this->city->name : null,
            'projects' => ProjectResource::collection($this->projects), // All projects
            'leads' => $this->leads ? InquiryResource::collection($this->leads->sortByDesc('created_at')->take(3)) : [],
            'invoices' => $this->invoices ? InvoiceResource::collection($this->invoices->sortByDesc('created_at')->take(3)) : [],
            'pending_tasks' => $this->projects->flatMap(function ($project) {
                return $project->tasks()->where('status', '!=', 'completed')->get();
            })->count(),
            'all_tasks' => $this->projects->flatMap(function ($project) {
                return $project->tasks()->get();
            })->count(),
            'completed_tasks' => $this->projects->flatMap(function ($project) {
                return $project->tasks()->where('status', 'completed')->get();
            })->count(),
            'inprogress_tasks' => $this->projects->flatMap(function ($project) {
                return $project->tasks()->where('status', 'in_progress')->get();
            })->count(),
        ];
    }
}
