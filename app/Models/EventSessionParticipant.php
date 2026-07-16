<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSessionParticipant extends Model
{
    protected $fillable = [
      'participant_id', 'session_id', 'status_id','method_id','attended_at','image'
    ];

    public function participant()
    {
        return $this->belongsTo('App\Models\Participant', 'participant_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo('App\Models\EventSession', 'session_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function getAttendedAtAttribute($value)
    {
        return ($value) ? date('F d, Y g:i a', strtotime($value)) : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('F d, Y g:i a', strtotime($value));
    }
}
