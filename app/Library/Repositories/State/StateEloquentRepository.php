<?php namespace ProjectManagement\Repositories\State;

use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\State;


class StateEloquentRepository extends EloquentRepository implements StateRepositoryInterface
{
    public function __construct()
    {
        $this->model = new State();
    }

    public function fetch_all($country_id)
    {
        if (!isset($country_id)){
            return $this->model->all();

        }else{
            return $this->model->where('country_id' , $country_id)->get();
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
        $state->country_id = $data['country_id'];
        $state->save();
        return $state;
    }

    public function update($id, $data)
    {
        $state = $this->find($id);
        if (isset($data['name'])) {
            $state->name = $data['name'];
        }
        if (isset($data['country_id'])) {
            $state->country_id = $data['country_id'];
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
        $query = $this->model::with('cities');

        if ($search_term) {
            $query->where(function ($query) use ($search_term) {
                $query
                      ->Where('name', 'like', "%{$search_term}%")
                      ->orWhereHas('cities', function ($query) use ($search_term) {
                          $query->where('name', 'like', "%{$search_term}%");
                      });
            });
        }
        $query->orderBy('created_at', 'desc');
        return $query->paginate($per_page, $columns, $page_name, $page);
    }
}
