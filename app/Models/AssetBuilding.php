<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetBuilding extends Model
{
    protected $fillable = [
        'name',
        'address',
        'longitude',
        'latitude',
        'barangay_code',
        'municipality_code',
        'province_code',
        'region_code',
        'station_id',
    ];

    public function barangay()
    {
        return $this->belongsTo('App\Models\LocationBarangay', 'barangay_code', 'code');
    }

    public function municipality()
    {
        return $this->belongsTo('App\Models\LocationMunicipality', 'municipality_code', 'code');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\LocationProvince', 'province_code', 'code');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\LocationRegion', 'region_code', 'code');
    }

    public function station()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'station_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? date('F j, Y', strtotime($value)) : null;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value ? ucwords(strtolower($value)) : $value;
    }
}
