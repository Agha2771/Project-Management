<?php namespace ProjectManagement\Repositories\Country;

use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\Country;
use ProjectManagement\Models\State;
use ProjectManagement\Models\City;

class CountryEloquentRepository extends EloquentRepository implements CountryRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Country();
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
        $country = new $this->model();
        $country->name = $data['name'];
        $country->save();
        return $country;
    }

    public function update($id, $data)
    {
        $country = $this->find($id);
        if (isset($data['name'])) {
            $country->name = $data['name'];
        }

        $country->save();
        return $country;
    }

    public function delete($id)
    {
        $country = $this->find($id);
        $country->delete();
    }
    public function paginate(int $per_page = 15, array $columns = ['*'], $page_name = 'page', $page = null, $search_term = null)
    {
        $query = $this->model::with('states' , 'states.cities');

        if ($search_term) {
            $query->where(function ($query) use ($search_term) {
                $query
                      ->Where('name', 'like', "%{$search_term}%")
                      ->orWhereHas('states', function ($query) use ($search_term) {
                          $query->where('name', 'like', "%{$search_term}%");
                      })
                      ->orWhereHas('states.cities', function ($query) use ($search_term) {
                        $query->where('title', 'like', "%{$search_term}%");
                    });
            });
        }
        $query->orderBy('created_at', 'desc');
        return $query->paginate($per_page, $columns, $page_name, $page);
    }
}
