<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOtherInformation extends Model
{
    protected $table = 'user_other_informations';

    protected $fillable = [
        'type',
        'value',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
