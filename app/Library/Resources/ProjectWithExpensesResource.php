<?php namespace ProjectManagement\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectWithExpensesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'budget' => $this->budget,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'currency' => new CurrencyResource($this->currency),
            'expenses' => [
                'total_expense' => $this->budget + $this->expenses()->sum('amount'),
                'paid_by_client' => $this->invoices->sum(function ($invoice) {
                    return $invoice->payments()->sum('amount_paid');
                }),
                'remaining_amount' => $this->budget + $this->expenses()->sum('amount') - $this->invoices->sum(function ($invoice) {
                    return $invoice->payments()->sum('amount_paid');
                }),
            ]
        ];
    }
}
