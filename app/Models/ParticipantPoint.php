<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantPoint extends Model
{
    protected $fillable = [
        'points','participant_id'
    ];

    public function participant()
    {
        return $this->belongsTo('App\Models\Participant', 'participant_id', 'id');
    }
}
