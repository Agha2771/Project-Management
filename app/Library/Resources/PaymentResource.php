<?php namespace ProjectManagement\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'hash' =>  $this->invoice->hash,
            'amount' => $this->invoice->amount,
            'amount_paid' => $this->amount_paid,
            'status' => $this->status,
            'payment_date' => $this->payment_date,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'remaining_amount' => $this->remaining_amount,
            'attachments' => $this->attachments
        ];
    }
}
