<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class ParticipantDetail extends Model
{
    protected $fillable = [
        'designation','affiliation_id','birthdate','others','type_id','sex_id','avatar','signature','is_4ps','is_pwd','is_ip','is_completed'
    ];

    protected $appends = ['age'];

    public function getAgeAttribute()
    {
        return $this->birthdate
            ? Carbon::parse($this->birthdate)->age   // Carbon automatically calculates age
            : null;
    }

    public function getAvatarAttribute($value)
    {
        if ($value === 'noavatar.jpg') {
            return asset('images/avatars/' . $value);
        }

        return Storage::disk('s3')->url($value) . '?v=' . ($this->updated_at?->timestamp ?? time());
    }

    public function sex()
    {
        return $this->belongsTo('App\Models\ListData', 'sex_id', 'id');
    }

    public function affiliation()
    {
        return $this->belongsTo('App\Models\ListName', 'affiliation_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListData', 'type_id', 'id');
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
