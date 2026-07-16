<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EventSessionActivity extends Model
{
    protected $fillable = [
       'start_time','end_time','activity','speaker_id','session_id','schedule_id', 'has_breakdown'
    ];

    public function schedule()
    {
        return $this->belongsTo('App\Models\EventSessionSchedule', 'schedule_id', 'id');
    }

    public function speaker()
    {
        return $this->belongsTo('App\Models\EventSpeakers', 'speaker_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo('App\Models\EventSession', 'session_id', 'id');
    }

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->format('g:i A'); // e.g. "1:00 PM"
    }

    public function getEndTimeAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('g:i A');
    }
}
