<?php

namespace ProjectManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function state(){
        return $this->belongsTo(State::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id', 'user_id');
    }

    public function leads()
    {
        return $this->hasMany(Inquiry::class, 'user_id', 'user_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_id', 'user_id');
    }

}
