<?php

namespace ProjectManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_assignees', 'task_id', 'user_id');
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function attachments()
    {
        return $this->morphMany(ProjectAttachment::class, 'attachable');
    }
}
