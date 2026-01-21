<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Request extends Model
{
    use LogsActivity;

    protected $fillable = [
        'code',
        'is_completed',
        'is_sender_viewed',
        'is_receiver_viewed',
        'type_id',
        'user_id'
    ];

    public function reservation()
    {
        return $this->hasOne('App\Models\RequestReservation', 'request_id');
    }

    public function overtime()
    {
        return $this->hasOne('App\Models\RequestOvertime', 'request_id');
    }

    public function leave()
    {
        return $this->hasOne('App\Models\RequestLeave', 'request_id');
    }

    public function event()
    {
        return $this->hasOne('App\Models\RequestEvent', 'request_id');
    }

    public function travel()
    {
        return $this->hasOne('App\Models\RequestTravel', 'request_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListData', 'type_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\RequestTag', 'request_id');
    }

    public function detail()
    {
        return $this->hasOne('App\Models\RequestDetail', 'request_id');
    }

    public function location()
    {
        return $this->hasOne('App\Models\RequestLocation', 'request_id');
    }

    public function dates()
    {
        return $this->hasMany('App\Models\RequestDate', 'request_id');
    }

    public function signatories()
    {
        return $this->hasMany('App\Models\RequestSignatory', 'request_id');
    }

    public function comments()
    {
         return $this->morphMany(RequestComment::class, 'commentable')
                ->with('replies');
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
        ->logOnly([
            'code',
            'is_completed',
            'is_sender_viewed',
            'is_receiver_viewed',
            'type_id',
            'user_id'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Request')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
