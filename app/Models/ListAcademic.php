<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListAcademic extends Model
{
    protected $fillable = [
        'name','short','type_id','level_id'
    ];

    public function level()
    {
        return $this->belongsTo('App\Models\ListData', 'level_id', 'id');
    }
}
