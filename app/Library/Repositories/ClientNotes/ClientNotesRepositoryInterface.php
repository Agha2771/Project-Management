<?php namespace ProjectManagement\Repositories\ClientNotes;

use ProjectManagement\Abstracts\RepositoryInterface;

interface ClientNotesRepositoryInterface extends RepositoryInterface
{
    public function fetch_all($id);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
