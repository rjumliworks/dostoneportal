<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSessionManager extends Model
{
    protected $fillable = [
       'type','user_id','session_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo('App\Models\EventSession', 'session_id', 'id');
    }
}
