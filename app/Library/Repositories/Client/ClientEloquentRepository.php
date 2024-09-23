<?php namespace ProjectManagement\Repositories\Client;


use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\Client;
use ProjectManagement\Models\User;

class ClientEloquentRepository extends EloquentRepository implements ClientRepositoryInterface
{
  public function __construct()
  {
    $this->model = new Client();
  }
  public function fetch_all()
  {
      $clients =  $this->model->all();
      return $clients;
  }

  public function find($id)
  {
      return $this->model->where('id' ,$id)->first();
  }

  public function create($data){
    $client = new $this->model();
    $client->business_name = $data['business_name'];
    $client->contact_person = $data['contact_person'];
    $client->email = $data['email'];
    $client->phone = $data['phone'];
    $client->address = $data['address'];
    $client->referance = $data['referance'];
    $client->created_by_id = auth()->user()->id;
    $client->user_id = $data['user_id'];
    $client->save();
    return $client;
}

public function update($id, $data)
{
    $client = $this->find($id);
    if (isset($data['business_name'])) {
        $client->business_name = $data['business_name'];
    }

    if (isset($data['contact_person'])) {
        $client->contact_person = $data['contact_person'];
    }

    if (isset($data['email'])) {
        $client->email = $data['email'];
    }

    if (isset($data['phone'])) {
        $client->phone = $data['phone'];
    }

    if (isset($data['address'])) {
        $client->address = $data['address'];
    }

    if (isset($data['referance'])) {
        $client->referance = $data['referance'];
    }

    $client->save();

    return $client;
}

  public function delete($id)
  {
      $client = $this->find($id);
      $client->delete();

  }
  public function paginate(int $perPage = 15, array $columns = ['*'], $pageName = 'page', $page = null, $searchTerm = null)
  {
      $query = $this->model::query();
      if ($searchTerm) {
          $query->where(function ($query) use ($searchTerm) {
              $query->where('business_name', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%")
                    ->orWhere('contact_person', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
          });
      }
      $query->orderBy('created_at', 'desc');
      return $query->paginate($perPage, $columns, $pageName, $page);
  }
}
