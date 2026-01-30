<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OldDtr extends Model
{
    protected $table = 'old_dtr';

    public function user()
    {
        return $this->belongsTo('App\Models\OldUser', 'user_id', 'id');
    }

}
