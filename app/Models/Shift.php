<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    public function shift()
    {
        return $this->hasOne('App\Models\UserOrganization', 'shift_id');
    }

    public function times()
    {
        return $this->hasMany('App\Models\ShiftTime', 'shift_id');
    }
}
