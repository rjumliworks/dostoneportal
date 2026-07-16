<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCsfEntry extends Model
{
    protected $fillable = [
        'rate',
        'comment',
        'attribute',
        'participant_id'
    ];

    public function feedbackable()
    {
        return $this->morphTo();
    }

    public function engageable()
    {
        return $this->morphMany('App\Models\ParticipantPointLog', 'engageable');
    }

    public function participant()
    {
        return $this->belongsTo('App\Models\Participant', 'participant_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\EventCsfRating', 'csf_id');
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
