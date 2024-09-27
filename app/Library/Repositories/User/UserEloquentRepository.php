<?php namespace ProjectManagement\Repositories\User;


use Illuminate\Support\Facades\Hash;
use ProjectManagement\Abstracts\EloquentRepository;
use ProjectManagement\Models\User;

class UserEloquentRepository extends EloquentRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function fetch_all_users($type){
        if($type == 'client'){
            return $this->model->select('id' , 'name')->where('user_type', 'client')->get();

        }else{
            return $this->model->select('id' , 'name')->where('user_type', 'platform')->get();
        }
    }

    public function find($id){
        return $this->model->where('id' , $id)->first();
    }

    public function create($data){

        $user = new $this->model();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->status = $data['status'];
        $user->profile_picture = $data['image'];
        $user->user_type = $data['user_type'] ?? 'platform';
        $user->password = $data['password'] ? Hash::make(value: $data['password']) : Hash::make(value: '12345678');
        $user->save();
        if ($user->user_type == 'platform'){
            $user->assignRole($data['role']);
        }
        return $user;
    }

    public function update($data,$id){
        $user = $this->model->where('id',$id)->first();

        if(isset($data['name'])){
            $user->name = $data['name'];
        }


        if(isset($data['profileImage']) and $data['profileImage']){
            $user->profile_picture = $data['profileImage'];
        }


        if(isset($data['password'])){
            $user->password = Hash::make($data['password']);
        }

        if(isset($data['status'])){
            $user->status = $data['status'];
        }

        if ($user->user_type == 'platform'){
            $user->assignRole($data['role']);
        }
        $user->save();
        return $user;
    }

    public function getByEmail($email){
        return $this->model->where('email',$email)->first();
    }

    public function resetPassword($data){
        $user = $this->model->where('forgot_token',$data['resetToken'])->first();
        $user->password = Hash::make($data['password']);
        $user->save();
        return $user;
    }

    public function getUserWithSameRole($name){
        return $this->model->role($name)->get();
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], $pageName = 'page', $page = null, $searchTerm = null)
    {
        $query = $this->model->with('roles')
        ->where('user_type', 'platform')
        ->whereDoesntHave('roles', function ($query) {
            $query->whereRaw('LOWER(name) = ?', ['admin']);
        });

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%")
                      ->orWhere('status', 'like', "%{$searchTerm}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage, $columns, $pageName, $page);
    }

    public function delete($id){
        $user = $this->model->find($id);
        $user->delete();
        return True;
    }

}
