<?php namespace ProjectManagement\Repositories\ProjectExpense;

use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\ProjectExpense;

class ProjectExpenseEloquentRepository extends EloquentRepository implements ProjectExpenseRepositoryInterface
{
    public function __construct()
    {
        $this->model = new ProjectExpense();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data , $invoice_id , $project_id)
    {
        $expense = new $this->model();
        $expense->project_id = $project_id ?? null;
        $expense->invoice_id = $invoice_id;
        $expense->description = $data['description'];
        $expense->qty = $data['qty'] ?? 1;
        $expense->discount = $data['discount'] ?? 0.00;
        $expense->amount = $data['amount'];
        $expense->save();
        return $expense;
    }

    public function update($id, $data)
    {
        $expense = $this->find($id);
        if ($expense) {
            $expense->description = $data['description'] ?? $expense->description;
            $expense->qty = $data['qty'] ?? $expense->qty;
            $expense->discount = $data['discount'] ?? $expense->discount;
            $expense->amount = $data['amount'] ?? $expense->amount;
            $expense->save();
        }

        return $expense;
    }
}
