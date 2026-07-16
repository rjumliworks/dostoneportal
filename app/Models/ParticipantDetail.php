<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ParticipantDetail extends Model
{
    protected $fillable = [
        'designation','affiliation','birthdate','type_id','sex_id','avatar','signature','is_4ps','is_pwd','is_ip','is_completed'
    ];

    protected $appends = ['age'];

    public function getAgeAttribute()
    {
        return $this->birthdate
            ? Carbon::parse($this->birthdate)->age   // Carbon automatically calculates age
            : null;
    }

    public function sex()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'sex_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'type_id', 'id');
    }

    public function setDesignationAttribute($value)
    {
        
        $this->attributes['designation'] = strtoupper($value);
    }

    public function setAffiliationAttribute($value)
    {
        $this->attributes['affiliation'] = strtoupper($value);
    }
}
