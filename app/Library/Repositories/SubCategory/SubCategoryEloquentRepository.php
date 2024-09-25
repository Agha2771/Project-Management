<?php namespace ProjectManagement\Repositories\SubCategory;

use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\SubCategory;

class SubCategoryEloquentRepository extends EloquentRepository implements SubCategoryRepositoryInterface
{
    public function __construct()
    {
        $this->model = new SubCategory();
    }

    public function fetch_all($category_id)
    {
        if (!isset($category_id)){
            return $this->model->all();

        }else{
            return $this->model->where('category_id' , $category_id)->get();
        }
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        $state = new $this->model();
        $state->name = $data['name'];
        $state->category_id = $data['category_id'];
        $state->save();
        return $state;
    }

    public function update($id, $data)
    {
        $state = $this->find($id);
        if (isset($data['name'])) {
            $state->name = $data['name'];
        }
        if (isset($data['category_id'])) {
            $state->category_id = $data['category_id'];
        }

        $state->save();
        return $state;
    }

    public function delete($id)
    {
        $state = $this->find($id);
        $state->delete();
    }

    public function paginate(int $per_page = 15, array $columns = ['*'], $page_name = 'page', $page = null, $search_term = null)
    {
        $query = $this->model::with('category');

        if ($search_term) {
            $query->where(function ($query) use ($search_term) {
                $query
                      ->Where('name', 'like', "%{$search_term}%")
                      ->orWhereHas('category', function ($query) use ($search_term) {
                          $query->where('name', 'like', "%{$search_term}%");
                      });
            });
        }
        $query->orderBy('created_at', 'desc');
        return $query->paginate($per_page, $columns, $page_name, $page);
    }
}
