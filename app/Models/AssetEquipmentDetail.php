<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetEquipmentDetail extends Model
{
    protected $table = 'asset_equipment_details';

    protected $fillable = [
        'equipment_id',
        'brand',
        'model',
        'price',
        'specification',
    ];

    protected $casts = [
        'specification' => 'array',
    ];

    public function equipment()
    {
        return $this->belongsTo('App\Models\AssetEquipment', 'equipment_id', 'id');
    }

    public function setBrandAttribute($value)
    {
        $this->attributes['brand'] = $value ? ucwords(strtolower($value)) : $value;
    }
}
