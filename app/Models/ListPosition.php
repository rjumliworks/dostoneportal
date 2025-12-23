<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','short','is_regular','salary_id'
    ];

    public function salary()
    {
        return $this->belongsTo('App\Models\ListSalary', 'salary_id', 'id');
    }
}
