<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RequestDate extends Model
{
    use LogsActivity;

    protected $fillable = [
        'time',
        'start',
        'end',
        'time_of_day',
        'request_id'
    ];

    public function getTimeAttribute($value)
    {
        return Carbon::parse($value)->format('g:i A'); 
    }

    public function getStartAttribute($value)
    {
        return date('F d, Y', strtotime($value));
    }

    public function getEndAttribute($value)
    {
        return date('F d, Y', strtotime($value));
    }

    public function request()
    {
        return $this->belongsTo('App\Models\Request', 'request_id', 'id');
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
        ->logOnly([
            'time',
            'start',
            'end'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Request Date')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
