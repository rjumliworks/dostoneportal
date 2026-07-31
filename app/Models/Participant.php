<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;

class Participant extends Authenticatable
{
    use HasApiTokens;
    
    protected $guarded = [];
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        $middleInitial = $this->middlename ? strtoupper($this->middlename[0]) . '.' : '';
        $parts = [trim($this->lastname) . ',', trim($this->firstname), $middleInitial, $this->suffix];
        return implode(' ', array_filter($parts));
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getHasCsfAttribute()
    {
        return $this->csfs->isNotEmpty();
    }

    protected $fillable = [
        'email','kradworkz','mobile','firstname','lastname','middlename','suffix','avatar', 'code', 'kradworkz','mobile_hash','is_completed'
    ];

    protected $hidden = [
        'kradworkz','mobile_hash'
    ];

    public function detail()
    {
        return $this->hasOne('App\Models\ParticipantDetail', 'participant_id');
    }

    public function sessions()
    {
        return $this->hasMany('App\Models\EventSessionParticipant', 'participant_id');
    }

    public function points()
    {
        return $this->hasOne('App\Models\ParticipantPoint', 'participant_id');
    }

    public function csfs()
    {
        return $this->hasMany('App\Models\EventCsfEntry', 'participant_id');
    }

    public function setFirstnameAttribute($value)
    {
        $value = ucfirst(strtolower($value));
        $this->attributes['firstname'] = Crypt::encryptString($value);
    }

    public function getFirstnameAttribute($value)
    {
        return ucwords(Crypt::decryptString($value));
    }

    public function setMiddlenameAttribute($value)
    {
        $value = ucfirst(strtolower($value));
        $this->attributes['middlename'] = Crypt::encryptString($value);
    }

    public function getMiddlenameAttribute($value)
    {
        return ucwords(Crypt::decryptString($value));
    }

    public function setLastnameAttribute($value)
    {
        $value = ucfirst(strtolower($value));
        $this->attributes['lastname'] = Crypt::encryptString($value);
    }

    public function getLastnameAttribute($value)
    {
        return ucwords(Crypt::decryptString($value));
    }

    public function setEmailAttribute($value)
    {
        $email = strtolower($value);
        $this->attributes['email'] = Crypt::encryptString($email);
        $this->attributes['kradworkz'] = hash('sha256', $email);
    }

    public function getEmailAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function setMobileAttribute($value)
    {
        $email = strtolower($value);
        $this->attributes['mobile'] = Crypt::encryptString($email);
        $this->attributes['mobile_hash'] = hash('sha256', $email);
    }

    public function getMobileAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('F d, Y g:i a', strtotime($value));
    }
}
