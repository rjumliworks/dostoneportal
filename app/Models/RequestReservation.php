<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestReservation extends Model
{
    protected $fillable = [
        'vehicle_id',
        'request_id',
        'driver_id'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Models\ListVehicle', 'vehicle_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id');
    }

    public function request()
    {
        return $this->belongsTo('App\Models\Request', 'request_id', 'id');
    }

    public function others()
    {
        return $this->hasMany('App\Models\RequestReservationOther', 'reservation_id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_id', 'id');
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
            'purpose',
            'remarks',
            'document',
            'vehicle_id',
            'request_id'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Reservation')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
