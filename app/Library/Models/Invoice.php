<?php

namespace ProjectManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function currency(){
        return $this->belongsTo(Currency::class);
    }

    public function project_expenses(){
        return $this->hasMany(ProjectExpense::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
