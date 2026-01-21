<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestReservationOther extends Model
{
    public function reservation()
    {
        return $this->belongsTo('App\Models\RequestReservation', 'reservation_id', 'id');
    }
}
