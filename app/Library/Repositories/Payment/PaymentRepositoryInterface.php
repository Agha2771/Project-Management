<?php namespace ProjectManagement\Repositories\Payment;

use ProjectManagement\Abstracts\RepositoryInterface;

interface PaymentRepositoryInterface extends RepositoryInterface
{
    public function fetch_all($client_id);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getPaymentAgainstClient($invoice_id);

    public function getPaymentsAgainstClient($invoice_id);
    public function paginate(int $per_age = 15, array $columns = ['*'], $page_name = 'page', $page = null, $search_term = null ,$sort_by);

}
