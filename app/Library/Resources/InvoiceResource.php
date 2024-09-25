<?php namespace ProjectManagement\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $latestPayment = $this->payments()->latest()->first();
        return [
            'id' => $this->id,
            'project' => $this->project ? [
                'id' => $this->project->id,
                'title' => $this->project->title,
            ]: null,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'title' => $this->user->name,
            ]: null,
            'invoice_date' => $this->invoice_date,
            'project_id' => $this->project_id,
            'user_id' => $this->user_id,
            'hash' => $this->hash,
            'due_date' => $this->due_date,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'status' => $this->status,
            'remaining_amount' => $latestPayment ? $latestPayment->remaining_amount : $this->amount,
            'project_expenses' => ProjectExpensesResource::collection($this->project_expenses)
        ];
    }
}
