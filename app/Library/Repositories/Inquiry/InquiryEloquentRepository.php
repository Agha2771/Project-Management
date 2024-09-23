<?php namespace ProjectManagement\Repositories\Inquiry;

use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\Inquiry;
use ProjectManagement\Models\Project;

class InquiryEloquentRepository extends EloquentRepository implements InquiryRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Inquiry();
    }

    public function fetch_all($client_id)
    {
        if($client_id){
            return $this->model->where('user_id' , $client_id)->get();

        }else{
            return $this->model->select('id' , 'title')->get();
        }
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        $project = new $this->model();
        $project->user_id = $data['client_id'];
        $project->title = $data['title'];
        $project->start_date = $data['start_date'] ?? null;
        $project->end_date = $data['end_date'] ?? null;
        $project->status =  $data['status'] ??  'in_progress';
        $project->budget = $data['budget'] ?? null;
        $project->description = $data['description'] ?? null;
        $project->currency_id = $data['currency_id'] ?? null;
        $project->save();
        return $project;
    }

    public function update($id, $data)
    {
        $project = $this->find($id);
        if ($project) {
            $project->user_id = $data['client_id'] ?? $project->user_id;
            $project->title = $data['title'] ?? $project->title;
            $project->start_date = $data['start_date'] ?? $project->start_date;
            $project->end_date = $data['end_date'] ?? $project->end_date;
            $project->status = $data['status'] ?? $project->status;
            $project->budget = $data['budget'] ?? $project->budget;
            $project->description = $data['description'] ?? $project->description;
            $project->currency_id = $data['currency_id'] ?? $project->currency_id;
            $project->save();
        }

        return $project;
    }

    public function delete($id)
    {
        $project = $this->find($id);
        if ($project) {
            $project->delete();
        }
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], $pageName = 'page', $page = null, $searchTerm = null)
    {
        $query = $this->model::with('user'); // Assuming 'user' is the relationship name

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('status', 'like', "%{$searchTerm}%")
                      ->orWhere('budget', 'like', "%{$searchTerm}%")
                      ->orWhereHas('user', function ($query) use ($searchTerm) {
                          $query->where('name', 'like', "%{$searchTerm}%");
                      });
            });
        }
        $query->orderBy('created_at', 'desc');
        return $query->paginate($perPage, $columns, $pageName, $page);
    }
}
