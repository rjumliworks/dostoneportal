<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSessionQuestion extends Model
{
    protected $fillable = [
      'participant_id', 'session_id', 'question'
    ];

    public function participant()
    {
        return $this->belongsTo('App\Models\Participant', 'participant_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo('App\Models\EventSession', 'session_id', 'id');
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
