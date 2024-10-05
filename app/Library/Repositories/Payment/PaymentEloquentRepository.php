<?php namespace ProjectManagement\Repositories\Payment;

use ProjectManagement\Resources\PaymentResource;
use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\Payment; // Update to use Payment model

class PaymentEloquentRepository extends EloquentRepository implements PaymentRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Payment(); // Update to initialize Payment model
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
        return $this->model->with('invoice')->find($id);
    }

    public function getPaymentAgainstClient($invoice_id){
        return $this->model->where('invoice_id' , $invoice_id)->latest()->first();
    }

    public function getPaymentsAgainstClient($invoice_id){
        return $this->model->where('invoice_id' , $invoice_id)->get();
    }
    public function create($data)
    {
        $payment = new $this->model();
        $payment->invoice_id = $data['invoice_id']; // Updated to include user_id
        $payment->amount_paid = $data['amount_paid']; // Updated to use amount_paid
        $payment->status = $data['status'] ?? 'pending'; // Updated for payment_type
        $payment->payment_date = $data['payment_date']; // Updated for payment_date
        $payment->description = $data['description'] ?? null; // Updated for description
        $payment->remaining_amount = $data['remaining_amount'] ?? 0; // Updated for description
        $payment->save();
        return $payment;
    }

    public function update($id, $data)
    {
        $payment = $this->find($id);
        if ($payment) {
            $payment->invoice_id = $data['invoice_id'] ?? $payment->invoice_id ;
            $payment->amount_paid = $data['amount_paid'] ??  $payment->amount_paid ;
            $payment->status = $data['status'] ?? $payment->status;
            $payment->payment_date = $data['payment_date'] ?? $payment->payment_date;
            $payment->description = $data['description'] ??  $payment->description;
            $payment->remaining_amount = $data['remaining_amount'] ?? $payment->remaining_amount;
            $payment->save();
        }
        return $payment;
    }

    public function delete($id)
    {
        $payment = $this->find($id);
        if ($payment) {
            $payment->delete();
        }
    }

    public function paginate(int $per_page = 15, array $columns = ['*'], $page_name = 'page', $page = null, $search_term = null, $sort_by = 'desc')
    {
        $query = $this->model::with('invoice');

        if ($search_term) {
            $query->where(function ($query) use ($search_term) {
                $query
                    ->where('status', 'like', "%{$search_term}%")
                    ->orWhereHas('invoice', function ($query) use ($search_term) {
                        $query->where('hash', 'like', "%{$search_term}%");
                    });
            });
        }

        $payments = $query->orderBy('created_at', $sort_by)->paginate($per_page, $columns, $page_name, $page);
        $result = [];

        foreach ($payments as $payment) {
            $invoiceId = $payment->invoice_id;

            if (!isset($result[$invoiceId])) {
                $result[$invoiceId] = [
                    'invoice_id' => $payment->invoice->hash,
                    'amount' => $payment->invoice->amount,
                    'status' => ($payment->invoice->payments()->sum('amount_paid') <  $payment->invoice->amount) ? 'partial' : ($payment->invoice->payments()->sum('amount_paid') === 0 ? 'unpaid' : 'full'),
                    'remaining_amount' => $payment->invoice->amount - $payment->invoice->payments()->sum('amount_paid'),
                    'total_paid' => $payment->invoice->payments()->sum('amount_paid'),
                    'payments' => []
                ];
            }
            $result[$invoiceId]['payments'][] = [
                'id' => $payment->id,
                'amount' => $payment->amount_paid,
                'created_at' => $payment->created_at,
            ];
        }
        $result = array_values($result);

        return [
            'data' => $result,
            'total_records' => $payments->total(),
            'current_page' => $payments->currentPage(),
            'total_pages' => $payments->lastPage(),
            'page_num' => $page,
            'per_page' => $per_page,
        ];
    }

}
