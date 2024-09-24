<?php

namespace ProjectManagement\Models;
use Illuminate\Database\Eloquent\Model;
class ProjectExpense extends Model
{
    protected $fillable = [
        'project_id', 'invoice_id', 'description', 'qty', 'discount', 'amount'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
