<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetEquipmentUser extends Model
{
    protected $table = 'asset_equipment_users';

    protected $fillable = [
        'equipment_id',
        'user_id',
        'start_at',
        'end_at',
    ];

    public function equipment()
    {
        return $this->belongsTo('App\Models\AssetEquipment', 'equipment_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
