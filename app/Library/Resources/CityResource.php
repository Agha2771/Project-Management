<?php namespace ProjectManagement\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'state' => $this->state,
            'country' => $this->country,
            'country_id' => $this->country->id,
            'state_id' => $this->state->id,
        ];
    }
}
