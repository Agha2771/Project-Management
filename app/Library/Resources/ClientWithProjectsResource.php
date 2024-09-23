<?php namespace ProjectManagement\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ClientWithProjectsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'business_name' => $this->business_name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'projects' => ProjectResource::collection($this->projects->sortByDesc('created_at')->take(3)),
            'leads' => ProjectResource::collection($this->leads->sortByDesc('created_at')->take(3)),
            'invoices' => InvoiceResource::collection($this->invoices->sortByDesc('created_at')->take(3)),
        ];
    }
}
