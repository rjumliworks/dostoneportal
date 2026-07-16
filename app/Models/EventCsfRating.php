<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCsfRating extends Model
{   
    public $timestamps = false;

    protected $fillable = [
        'answer',
        'rating',
        'importance',
        'question_id',
        'csf_id'
    ];

    public function csf()
    {
        return $this->belongsTo('App\Models\EventCsfEntry', 'csf_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo('App\Models\EventCsfQuestion', 'question_id', 'id');
    }
}
