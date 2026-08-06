<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPdsDeclaration extends Model
{
    protected $fillable = [
        'related_third_degree',
        'related_third_degree_details',
        'related_fourth_degree',
        'related_fourth_degree_details',
        'admin_offense_found_guilty',
        'admin_offense_details',
        'criminally_charged',
        'criminal_charge_details',
        'criminal_charge_date_filed',
        'criminal_charge_case_status',
        'convicted_crime',
        'convicted_crime_details',
        'separated_from_service',
        'separated_from_service_details',
        'election_candidate',
        'election_candidate_details',
        'resigned_to_campaign',
        'resigned_to_campaign_details',
        'immigrant_status',
        'immigrant_status_country',
        'indigenous_group_member',
        'indigenous_group_details',
        'is_pwd',
        'pwd_id_number',
        'is_solo_parent',
        'solo_parent_id_number',
        'government_id_type',
        'government_id_number',
        'government_id_issued_at',
        'declared_at',
        'photo_path',
        'thumbmark_path',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
