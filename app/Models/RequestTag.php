<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RequestTag extends Model
{
    use LogsActivity;

    protected $fillable = [
        'is_sender_viewed',
        'is_receiver_viewed',
        'is_driver',
        'user_id',
        'division_id',
        'signatory_id',
        'status_id',
        'request_id'
    ];

    public function statusable()
    {
        return $this->morphMany('App\Models\RequestStatus', 'statusable');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
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
            'is_sender_viewed',
            'is_receiver_viewed',
            'user_id',
            'request_id'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Request Tag')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
