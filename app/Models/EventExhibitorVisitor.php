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
}
