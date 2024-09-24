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
            'hash' => $this->hash,
            'due_date' => $this->due_date,
            'currency' => $this->currency->sign,
            'amount' => $this->amount,
            'status' => $this->status,
            'project_expenses' => ProjectExpensesResource::collection($this->project_expenses)
        ];
    }
}
