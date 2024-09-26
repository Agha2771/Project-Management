<?php namespace ProjectManagement\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'description' => $this->description,
            'amount' => $this->amount,
            'qty' => $this->quantity,
            'subcategory' => $this->subcategory,
            'category_id' => $this->subcategory->category->id,
            'subcategory_id' => $this->subcategory_id
        ];
    }
}
