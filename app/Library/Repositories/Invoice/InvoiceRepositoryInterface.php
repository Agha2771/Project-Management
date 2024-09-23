<?php namespace ProjectManagement\Repositories\Invoice;

use ProjectManagement\Abstracts\RepositoryInterface;

interface InvoiceRepositoryInterface extends RepositoryInterface
{
    public function fetch_all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
