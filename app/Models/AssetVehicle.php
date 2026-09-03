<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasMaintenance;

class AssetVehicle extends Model
{
    use HasMaintenance;

    protected $fillable = ['code','name','plate','type_id','status_id','station_id','driver_id','is_available','acquired_at'];

    public function reservations()
    {
       return $this->hasMany('App\Models\RequestReservation', 'vehicle_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListData', 'type_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id');
    }

    public function station()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'station_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }
}
