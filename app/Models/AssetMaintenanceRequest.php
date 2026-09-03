<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMaintenanceRequest extends Model
{
    protected $table = 'asset_maintenance_requests';

    protected $fillable = [
        'code',
        'maintainable_type',
        'maintainable_id',
        'requested_by',
        'location',
        'work_requested',
        'problem_description',
        'priority_id',
        'status_id',
        'remarks',
        'requested_at',
    ];

    public function maintainable()
    {
        return $this->morphTo();
    }

    public function requester()
    {
        return $this->belongsTo('App\Models\User', 'requested_by', 'id');
    }

    public function priority()
    {
        return $this->belongsTo('App\Models\ListData', 'priority_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\ListStatus', 'status_id', 'id');
    }

    public function record()
    {
        return $this->hasOne('App\Models\AssetMaintenanceRecord', 'request_id', 'id');
    }
}
