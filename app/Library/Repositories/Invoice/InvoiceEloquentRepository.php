<?php namespace ProjectManagement\Repositories\Invoice;

use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\Invoice;
use Carbon\Carbon;

class InvoiceEloquentRepository extends EloquentRepository implements InvoiceRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Invoice();
    }

    public function fetch_all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        $invoice = new $this->model();
        $invoice->invoice_date = $data['invoice_date'] ;
        $invoice->currency_id = $data['currency_id'] ;
        $invoice->user_id = $data['user_id'];
        $invoice->project_id = $data['project_id'] ?? null;
        $invoice->sent_time = Carbon::now();
        $invoice->amount = $data['amount'];
        $invoice->status = $data['status'] ?? 'pending';
        $invoice->save();
        return $invoice;
    }

    public function update($id, $data)
    {
        $invoice = $this->find($id);
        if ($invoice) {
            $invoice->invoice_date = $data['invoice_date'] ?? $invoice->invoice_date;
            $invoice->user_id = $data['user_id'] ?? $invoice->user_id;
            $invoice->currency_id = $data['currency_id'] ?? $invoice->currency_id ;
            $invoice->project_id = $data['project_id'] ?? $invoice->project_id;
            $invoice->sent_time = $data['sent_time'] ?? $invoice->sent_time;
            $invoice->amount = $data['amount'] ?? $invoice->amount;
            $invoice->status = $data['status'] ?? $invoice->status;
            $invoice->save();
        }
        return $invoice;
    }

    public function delete($id)
    {
        $invoice = $this->find($id);
        if ($invoice) {
            $invoice->delete();
        }
    }
}
