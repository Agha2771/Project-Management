<?php

namespace ProjectManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function attachments()
    {
        return $this->morphMany(ProjectAttachment::class, 'attachable');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lead()
    {
        return $this->belongsTo(Inquiry::class , 'inquiry_id' , 'id');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'project_assignees', 'project_id', 'user_id');
    }

    public function expenses(){
        return  $this->hasMany(ProjectExpense::class);
    }

    public function invoices(){
        return  $this->hasMany(Invoice::class);
    }

}
