<?php namespace ProjectManagement\Repositories\Account;

use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\Account;

class AccountEloquentRepository extends EloquentRepository implements AccountRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Account();
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
        $account = new $this->model();
        $account->title = $data['title'];
        $account->save();
        return $account;
    }

    public function update($id, $data)
    {
        $account = $this->find($id);
        if ($account) {
            $account->title = $data['title'] ?? $account->title;
            $account->save();
        }
        return $account;
    }

    public function delete($id)
    {
        $account = $this->find($id);
        if ($account) {
            $account->delete();
        }
    }
}
