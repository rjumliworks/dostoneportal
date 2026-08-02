<?php

namespace App\Services\Trace\Signatory;

use Hashids\Hashids;
use App\Models\OrgChart;
use App\Http\Resources\Trace\Signatory\ViewResource;
use App\Http\Resources\Trace\Signatory\DesignationResource;

class ViewClass
{
    public function designations(){
        $data = OrgChart::with('designation','assigned')
        ->with([
            // 'designationable.schedules.user:id,email,username',
            // 'designationable.schedules.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            'designationable.schedules' => function ($q) {
                $q->where('is_completed', 0)
                  ->where('is_ongoing',1)
                  ->where('is_designated', 0)
                  ->whereDate('end_at', '>=', now()->toDateString())
                  ->with([
                      'user:id,email,username',
                      'user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
                  ]);
            },
            'designationable.user:id,email,username',
            'designationable.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            'designationable.oic:id,email,username',
            'designationable.oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar'
        ])
        ->with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar','oic:id,email,username','oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar')
        ->orderBy('order','ASC')
        ->get();
        return DesignationResource::collection($data);
    }

    public function designation($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);
        $data = OrgChart::with('designation','assigned')
        ->with([
            'designationable.schedules' => function ($q) {
                $q->where('is_completed', 0)
                  ->where('is_ongoing',1)
                  ->where('is_designated', 0)
                  ->whereDate('end_at', '>=', now()->toDateString())
                  ->with([
                      'user:id,email,username',
                      'user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
                  ]);
            },
            'designationable.user:id,email,username',
            'designationable.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            'designationable.oic:id,email,username',
            'designationable.oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar'
        ])
        ->with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar','oic:id,email,username','oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar')
        ->orderBy('order','ASC')
        ->where('id', $id)
        ->first();
        return new DesignationResource($data);
    }
}
