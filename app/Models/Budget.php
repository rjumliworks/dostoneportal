<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    public function type()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'type_id', 'id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\BudgetItem', 'budget_id');
    }

}
