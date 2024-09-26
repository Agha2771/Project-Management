<?php

namespace ProjectManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function subcategory(){
        return $this->belongsTo(SubCategory::class);
    }
}
