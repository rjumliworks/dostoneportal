<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Schedule extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'venue',
        'description',
        'is_allday',
        'start',
        'end',
        'event_id',
        'agency_id',
        'user_id'
    ];

    public function stations()
    {
        return $this->hasMany('App\Models\ScheduleStation', 'schedule_id');
    }
    
    public function event()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'event_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('F d, Y g:i a', strtotime($value));
    }
    
    public function updateIfDirty(array $attributes){
        $this->fill($attributes);
        $dirtyAttributes = $this->getDirty();
        if(!empty($dirtyAttributes)) {
            $originalAttributes = array_intersect_key($this->getOriginal(), $dirtyAttributes);
            $updated = $this->update($dirtyAttributes);
            return $updated;
        }
        return false;
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly([ 'title','venue','description','is_allday','start','end',])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Schedule')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
