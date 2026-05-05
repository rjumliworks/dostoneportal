<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    public function budget()
    {
        return $this->belongsTo('App\Models\Budget', 'budget_id', 'id');
    }
}
