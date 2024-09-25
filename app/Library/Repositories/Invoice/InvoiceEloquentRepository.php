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

    public function fetch_all($client_id)
    {
        if(!isset($client_id)){
            return $this->model->all();
        }else{
            return $this->model->where('user_id' , $client_id)->get();
        }
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        $invoice = new $this->model();
        $invoice->invoice_date = $data['invoice_date'];
        $invoice->currency_id = $data['currency_id'];
        $invoice->user_id = $data['user_id'];
        $invoice->project_id = $data['project_id'] ?? null;
        $invoice->sent_time = Carbon::now();
        $invoice->amount = $data['amount'];
        $invoice->status = 'pending';
        $uniqueNumber = strtoupper(uniqid());
        $invoice->hash = '#INV' . substr($uniqueNumber, 0, 16);
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
            // $invoice->status = $data['status'] ?? $invoice->status;
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

    public function paginate(int $per_page = 15, array $columns = ['*'], $page_name = 'page', $page = null, $search_term = null , $sort_by=null)
    {
        $query = $this->model::with('project' , 'user');

        if ($search_term) {
            $query->where(function ($query) use ($search_term) {
                $query
                      ->Where('status', 'like', "%{$search_term}%")
                      ->orWhere('hash', 'like', "%{$search_term}%")
                      ->orWhereHas('user', function ($query) use ($search_term) {
                          $query->where('name', 'like', "%{$search_term}%");
                      })
                      ->orWhereHas('project', function ($query) use ($search_term) {
                        $query->where('title', 'like', "%{$search_term}%");
                    });
            });
        }
        $query->orderBy('created_at', $sort_by);
        return $query->paginate($per_page, $columns, $page_name, $page);
    }
}
