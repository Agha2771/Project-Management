<?php namespace ProjectManagement\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'states' => $this->states,
        ];
    }
}
