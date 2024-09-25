<?php namespace ProjectManagement\Repositories\City;

use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\City;


class CityEloquentRepository extends EloquentRepository implements CityRepositoryInterface
{
    public function __construct()
    {
        $this->model = new City();
    }

    public function fetch_all($state_id)
    {
        if (!isset($state_id)){
            return $this->model->all();

        }else{
            return $this->model->where('state_id' , $state_id)->get();
        }
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        $city = new $this->model();
        $city->name = $data['name'];
        $city->state_id = $data['state_id'];
        $city->country_id = $data['country_id'];
        $city->save();
        return $city;
    }

    public function update($id, $data)
    {
        $city = $this->find($id);
        if (isset($data['name'])) {
            $city->name = $data['name'];
        }
        if (isset($data['state_id'])) {
            $city->state_id = $data['state_id'];
        }
        if (isset($data['country_id'])) {
            $city->country_id = $data['country_id'];
        }

        $city->save();
        return $city;
    }

    public function delete($id)
    {
        $city = $this->find($id);
        $city->delete();
    }

    public function paginate(int $per_page = 15, array $columns = ['*'], $page_name = 'page', $page = null, $search_term = null)
    {
        $query = $this->model::with('country' , 'state');
        if ($search_term) {
            $query->where(function ($query) use ($search_term) {
                $query
                      ->Where('name', 'like', "%{$search_term}%")
                      ->orWhereHas('country', function ($query) use ($search_term) {
                          $query->where('name', 'like', "%{$search_term}%");
                      })                  ->orWhereHas('state', function ($query) use ($search_term) {
                        $query->where('name', 'like', "%{$search_term}%");
                    })
                      ;
            });
        }
        $query->orderBy('created_at', 'desc');
        return $query->paginate($per_page, $columns, $page_name, $page);
    }

}
