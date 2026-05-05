<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    public function budget()
    {
        return $this->belongsTo('App\Models\Budget', 'budget_id', 'id');
    }

    public function allocations()
    {
        return $this->hasMany('App\Models\BudgetAllocation', 'project_id');
    }
}
