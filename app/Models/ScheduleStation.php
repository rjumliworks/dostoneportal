<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleStation extends Model
{
    protected $fillable = [
        'religion_id',
        'station_id',
        'schedule_id'
    ];

    public function schedule()
    {
        return $this->belongsTo('App\Models\Schedule', 'schedule_id', 'id');
    }

    public function religion()
    {
        return $this->belongsTo('App\Models\ListData', 'religion_id', 'id');
    }

    public function station()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'station_id', 'id');
    }
}
