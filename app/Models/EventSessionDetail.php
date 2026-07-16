<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSessionDetail extends Model
{
    protected $fillable = [
       'capacity',
       'attendees',
       'description',
       'session_id'
    ];

    public function session()
    {
        return $this->belongsTo('App\Models\EventSession', 'event_id', 'id');
    }
}
