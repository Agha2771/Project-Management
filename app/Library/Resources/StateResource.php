<?php namespace ProjectManagement\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'country_id' => $this->country_id,
            'country' => $this->country,
            'cities' => $this->cities,
        ];
    }
}
