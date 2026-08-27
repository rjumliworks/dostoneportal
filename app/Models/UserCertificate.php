<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class UserCertificate extends Model
{
    protected $fillable = [
        'file',
        'password',
        'is_checked',
        'expires_at',
        'signature',
        'user_id'
    ];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    protected $hidden = [
        'password'
    ];

    public function user()     {
        return $this->belongsTo(User::class);
    }

    /**
     * Encrypt the p12 password on write so it is never stored in plaintext.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = (! is_null($value) && $value !== '')
            ? Crypt::encryptString($value)
            : $value;
    }

    /**
     * Decrypt the p12 password on read. Falls back to the raw value for
     * legacy rows that were stored in plaintext before encryption was
     * introduced.
     */
    public function getPasswordAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return $value;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }
}
