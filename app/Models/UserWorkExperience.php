<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWorkExperience extends Model
{
    protected $fillable = [
        'start_at',
        'end_at',
        'position_title',
        'department_agency',
        'monthly_salary',
        'salary_grade',
        'appointment_status',
        'is_government',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
