<?php namespace ProjectManagement\Repositories\Payment;

use ProjectManagement\Abstracts\RepositoryInterface;

interface PaymentRepositoryInterface extends RepositoryInterface
{
    public function fetch_all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getPaymentAgainstClient($invoice_id);
}
