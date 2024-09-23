<?php namespace ProjectManagement\Repositories\ClientNotes;



use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\ClientNote;

class ClientNotesEloquentRepository extends EloquentRepository implements ClientNotesRepositoryInterface
{
  public function __construct()
  {
    $this->model = new ClientNote();
  }
  public function fetch_all($id)
  {
      $client_notes =  $this->model->where('user_id' , $id)->get();
      return $client_notes;
  }

  public function find($id)
  {
      return $this->model->where('id' ,$id)->first();
  }

  public function create($data){
    $client_note = new $this->model();
    $client_note->name = $data['name'];
    $client_note->description = $data['description'];
    $client_note->user_id = auth()->user()->id;
    $client_note->inquiry_id = $data['inquiry_id'];
    $client_note->save();
    return $client_note;
}

public function update($id, $data)
{
    $client_note = $this->find($id);
    if (isset($data['name'])) {
        $client_note->name = $data['name'];
    }

    if (isset($data['description'])) {
        $client_note->description = $data['description'];
    }

    $client_note->save();

    return $client_note;
}

  public function delete($id)
  {
      $client_note = $this->find($id);
      $client_note->delete();
      
  }
}
