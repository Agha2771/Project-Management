<?php namespace ProjectManagement\Repositories\Expense;

use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\Expense;

class ExpenseEloquentRepository extends EloquentRepository implements ExpenseRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Expense();
    }

    public function fetch_all($category_id = null)
    {
        if (!isset($category_id)) {
            return $this->model->all();
        } else {
            return $this->model->where('category_id', $category_id)->get();
        }
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        $expense = new $this->model();
        $expense->description = $data['description'];
        $expense->subcategory_id = $data['subcategory_id'];
        $expense->amount = $data['amount'];
        $expense->quantity = $data['qty'];
        $expense->save();

        return $expense;
    }

    public function update($id, $data)
    {
        $expense = $this->find($id);

        if (isset($data['description'])) {
            $expense->description = $data['description'];
        }
        if (isset($data['subcategory_id'])) {
            $expense->subcategory_id = $data['subcategory_id'];
        }
        if (isset($data['amount'])) {
            $expense->amount = $data['amount'];
        }
        if (isset($data['qty'])) {
            $expense->quantity = $data['qty'];
        }
        $expense->save();
        return $expense;
    }

    public function delete($id)
    {
        $expense = $this->find($id);
        if ($expense) {
            $expense->delete();
        }
    }

    public function paginate(int $per_page = 15, array $columns = ['*'], $page_name = 'page', $page = null, $search_term = null)
    {
        $query = $this->model::with( 'subcategory');

        if ($search_term) {
            $query->where(function ($query) use ($search_term) {
                $query->where('description', 'like', "%{$search_term}%")
                      ->orWhereHas('subcategory.category', function ($query) use ($search_term) {
                          $query->where('name', 'like', "%{$search_term}%");
                      })
                      ->orWhereHas('subcategory', function ($query) use ($search_term) {
                          $query->where('name', 'like', "%{$search_term}%");
                      })
                      ;
            });
        }

        $query->orderBy('created_at', 'desc');
        return $query->paginate($per_page, $columns, $page_name, $page);
    }
}
