<?php

namespace ProjectManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAttachment extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'project_attachments';

    public function attachable()
    {
        return $this->morphTo();
    }
}
