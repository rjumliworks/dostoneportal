<?php

namespace App\Services\Portal\Approval;

use Hashids\Hashids;
use App\Models\Request;
use App\Models\RequestLeave;
use App\Models\RequestTravel;
use App\Models\RequestSignatory;
use App\Http\Resources\Portal\Approval\TravelResource;
use App\Http\Resources\Portal\Approval\LeaveResource;
use App\Http\Resources\Portal\Approval\OvertimeResource;
use App\Http\Resources\Portal\Approval\ReservationResource;

class ShowClass
{
    public function reservation($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);
        
        $data = RequestSignatory::with([
            'status',
            'statusable',
            'request.reservation.vehicle',
            'request.reservation.driver',
            'request.comments.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id','request.comments.replies.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.type',
            'request.dates',
            'request.detail',
            'request.user:id',
            'request.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.signatories.division','request.signatories.approved','request.signatories.recommended',
            'request.location.region:code,name,region','request.location.province:code,name','request.location.municipality:code,name','request.location.barangay:code,name'
        ])
        ->where('id',$id)
        ->first();

        return new ReservationResource($data);
    }

    public function travel($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = RequestSignatory::with([
            'division',
            'status',
            'statusable',
            'request.travel.mode',
            'request.travel.expense',
            'request.comments.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id','request.comments.replies.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.type',
            'request.dates',
            'request.detail',
            'request.user:id',
            'request.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.signatories.division','request.signatories.approved','request.signatories.recommended',
            'request.location.region:code,name,region','request.location.province:code,name','request.location.municipality:code,name','request.location.barangay:code,name'
        ])
        ->where('id',$id)
        ->first();

        return new TravelResource($data);
    }

    public function overtime($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = RequestSignatory::with([
            'status',
            'statusable',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            'request.comments.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.comments.replies.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.type',
            'request.dates',
            'request.detail',
            'request.user:id',
            'request.overtime.status',
            'request.comments.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            'request.comments.replies.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            'request.user.profile:user_id,firstname,middlename,lastname,suffix_id',
            'request.signatories.division','request.signatories.approved','request.signatories.recommended'
        ])
        ->where('id',$id)
        ->first();

        return new OvertimeResource($data);
    }

    public function leave($code){

        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code)[0];

        $data = RequestSignatory::with([
            'status',
            'statusable',
            'request.leave.detail',
            'request.leave.type',
            'request.leave.credits.log','request.leave.credits.credit.leave',
            'request.comments.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.comments.replies.user.profile:user_id,firstname,middlename,lastname,avatar,avatar,suffix_id',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.type',
            'request.dates',
            'request.detail',
            'request.user:id',
            'request.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.signatories.division','request.signatories.approved','request.signatories.approved.signatory.designationable.designation','request.signatories.recommended','request.signatories.recommended.signatory.designationable.designation'
        ])
        ->where('id',$id)
        ->first();

        return new LeaveResource($data);
    }
}
