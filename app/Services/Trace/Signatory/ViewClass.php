<?php

namespace App\Services\Trace\Signatory;

use Hashids\Hashids;
use App\Models\OrgChart;
use App\Models\OrgSignatorySchedule;
use App\Http\Resources\Trace\Signatory\ViewResource;
use App\Http\Resources\Trace\Signatory\DesignationResource;
use App\Http\Resources\Trace\Signatory\ScheduleResource;

class ViewClass
{
    public function list($request){
        $data = OrgSignatorySchedule::with([
            'user:id,email,username',
            'user.profile:user_id,firstname,middlename,lastname,suffix_id,sex_id,avatar',
            'user.profile.suffix:id,name',
            'user.profile.sex:id,name',
            'user.organization:id,user_id,division_id,position_id',
            'user.organization.division:id,name',
            'user.organization.position:id,name',
        ])
        ->where('signatory_id', $request->signatory_id)
        ->orderBy('start_at', 'desc')
        ->paginate($request->count);
        return ScheduleResource::collection($data);
    }

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
                      'user.profile:user_id,firstname,middlename,lastname,suffix_id,sex_id,avatar',
            'user.profile.suffix:id,name',
            'user.profile.sex:id,name',
                      'user.organization:id,user_id,division_id,position_id',
                      'user.organization.division:id,name',
                      'user.organization.position:id,name',
                  ]);
            },
            // 'designationable.user:id,email,username',
            // 'designationable.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            // 'designationable.oic:id,email,username',
            // 'designationable.oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar'
        ])
        ->with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id,sex_id,avatar','user.profile.suffix:id,name','user.profile.sex:id,name')
        ->with('oic:id,email,username','oic.profile:user_id,firstname,middlename,lastname,suffix_id,sex_id,avatar','oic.profile.suffix:id,name','oic.profile.sex:id,name')
        ->with('user.organization:id,user_id,division_id,position_id','user.organization.division:id,name','user.organization.position:id,name')
        ->with('oic.organization:id,user_id,division_id,position_id','oic.organization.division:id,name','oic.organization.position:id,name')
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
                      'user.profile:user_id,firstname,middlename,lastname,suffix_id,sex_id,avatar',
            'user.profile.suffix:id,name',
            'user.profile.sex:id,name',
                      'user.organization:id,user_id,division_id,position_id',
                      'user.organization.division:id,name',
                      'user.organization.position:id,name',
                  ]);
            },
            // 'designationable.oicHistory.user:id,email,username',
            // 'designationable.oicHistory.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            // 'designationable.user:id,email,username',
            // 'designationable.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            // 'designationable.oic:id,email,username',
            // 'designationable.oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar'
        ])
        ->with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id,sex_id,avatar','user.profile.suffix:id,name','user.profile.sex:id,name')
        ->with('oic:id,email,username','oic.profile:user_id,firstname,middlename,lastname,suffix_id,sex_id,avatar','oic.profile.suffix:id,name','oic.profile.sex:id,name')
        ->with('user.organization:id,user_id,division_id,position_id','user.organization.division:id,name','user.organization.position:id,name')
        ->with('oic.organization:id,user_id,division_id,position_id','oic.organization.division:id,name','oic.organization.position:id,name')
        ->orderBy('order','ASC')
        ->where('id', $id)
        ->first();
        return new DesignationResource($data);
    }
}
