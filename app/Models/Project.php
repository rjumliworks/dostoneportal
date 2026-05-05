<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function allocations()
    {
        return $this->hasMany('App\Models\BudgetAllocation', 'project_id');
    }
}
