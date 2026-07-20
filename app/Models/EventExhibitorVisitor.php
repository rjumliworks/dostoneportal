<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventExhibitorVisitor extends Model
{
    protected $fillable = [
       'has_voted',
       'voted_at',
       'exhibitor_id',
       'participant_id'
    ];

    public function engageable()
    {
        return $this->morphMany('App\Models\ParticipantPointLog', 'engageable');
    }

    public function participant()
    {
        return $this->belongsTo('App\Models\Participant', 'participant_id', 'id');
    }

    public function exhibitor()
    {
        return $this->belongsTo('App\Models\EventExhibitor', 'exhibitor_id', 'id');
    }

    public function getVotedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
}
