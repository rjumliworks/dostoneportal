<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSessionSchedule extends Model
{
     protected $fillable = [
       'date',
       'time_of_day',
       'start_time',
       'end_time',
       'session_id'
    ];

    public function session()
    {
        return $this->belongsTo('App\Models\EventSession', 'event_id', 'id');
    }
}
