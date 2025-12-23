<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListVehicle extends Model
{
    public function reservations()
    {
       return $this->hasMany('App\Models\RequestReservation', 'vehicle_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id');
    }
}
