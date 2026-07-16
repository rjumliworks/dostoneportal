<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSessionActivityBreakdown extends Model
{
    protected $fillable = [
       'start_time','end_time','activity','speaker_id','activity_id',
    ];
}
