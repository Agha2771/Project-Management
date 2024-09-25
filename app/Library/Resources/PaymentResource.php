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
            'amount_paid' => $this->amount_paid, // Update to amount_paid
            'status' => $this->status, // Update to payment_type
            'payment_date' => $this->payment_date, // Update to payment_date
            'description' => $this->description, // Update to description
            'created_at' => $this->created_at, // Optional: include timestamps if needed
            'updated_at' => $this->updated_at, // Optional: include timestamps if needed
            'remaining_amount' => $this->remaining_amount, // Optional: include timestamps if needed
        ];
    }
}
