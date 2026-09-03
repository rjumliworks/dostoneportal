<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMaintenanceRecord extends Model
{
    protected $table = 'asset_maintenance_records';

    protected $fillable = [
        'maintainable_type',
        'maintainable_id',
        'request_id',
        'type_id',
        'status_id',
        'date',
        'operation_performed',
        'remarks',
        'performed_by',
        'cost',
        'attachment',
        'next_due',
    ];

    public function maintainable()
    {
        return $this->morphTo();
    }

    public function request()
    {
        return $this->belongsTo('App\Models\AssetMaintenanceRequest', 'request_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListData', 'type_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function performer()
    {
        return $this->belongsTo('App\Models\User', 'performed_by', 'id');
    }
}
