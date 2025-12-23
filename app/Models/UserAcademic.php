<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAcademic extends Model
{
    protected $fillable = ['school_id','course_id','is_ongoing','level_id','user_id','graduated_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function school()
    {
        return $this->belongsTo('App\Models\ListAcademic', 'school_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\ListAcademic', 'course_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo('App\Models\ListData', 'level_id', 'id');
    }
}
