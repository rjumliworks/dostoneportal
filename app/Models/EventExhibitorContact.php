<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventExhibitorContact extends Model
{
    protected $fillable = [
       'name',
       'contact_no',
       'email',
       'exhibitor_id'
    ];

    public function exhibitor()
    {
        return $this->belongsTo('App\Models\EventExhibitor', 'event_id', 'id');
    }
}
