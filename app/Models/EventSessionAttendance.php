<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSessionAttendance extends Model
{
    protected $fillable = [
        'date', 'attended_at', 'image', 'method_id', 'participant_id', 'session_id'
    ];

    public function participant()
    {
        return $this->belongsTo('App\Models\Participant', 'participant_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo('App\Models\EventSession', 'session_id', 'id');
    }

    public function getAttendedAtAttribute($value)
    {
        return ($value) ? date('F d, Y g:i a', strtotime($value)) : null;
    }
}
