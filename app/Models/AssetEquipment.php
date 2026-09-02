<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetEquipment extends Model
{
    protected $table = 'asset_equipments';

    protected $fillable = [
        'code',
        'old_code',
        'name',
        'type_id',
        'station_id',
        'maintenance_plan',
        'maintenance_due',
        'remarks',
        'status_id',
        'user_id',
        'acquired_at',
    ];

    public function type()
    {
        return $this->belongsTo('App\Models\ListData', 'type_id', 'id');
    }

    public function station()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'station_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function detail()
    {
        return $this->hasOne('App\Models\AssetEquipmentDetail', 'equipment_id', 'id');
    }

    public function assignments()
    {
        return $this->hasMany('App\Models\AssetEquipmentUser', 'equipment_id', 'id');
    }

    public function currentAssignment()
    {
        return $this->hasOne('App\Models\AssetEquipmentUser', 'equipment_id', 'id')->whereNull('end_at');
    }
}
