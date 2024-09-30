<?php namespace ProjectManagement\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientWithPaymentsResource extends JsonResource
{
    public function toArray($request)
    {
        $projectsCount = $this->projects()->count();
        $completedProjectsCount = $this->projects()->where('status', 'completed')->count();
        $totalAmount = $this->invoices()->sum('amount');
        $amountPaid = $this->invoices()->with('payments')->get()->sum(function ($invoice) {
            return $invoice->payments->sum('amount_paid');
        });

        return [
            'projects_count' => $projectsCount,
            'completed_projects_count' => $completedProjectsCount,
            'total_amount' => $totalAmount,
            'amount_paid' => $amountPaid,
            'remaining_amount' => $totalAmount - $amountPaid,
        ];
    }
}
