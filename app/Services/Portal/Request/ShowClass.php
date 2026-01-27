<?php

namespace App\Services\Portal\Request;

use Hashids\Hashids;
use App\Models\Request;
use App\Models\RequestLeave;
use App\Models\RequestTravel;
use App\Models\RequestSignatory;
use App\Models\RequestReservation;
use App\Http\Resources\Portal\Request\TravelResource;
use App\Http\Resources\Portal\Request\LeaveResource;
use App\Http\Resources\Portal\Request\OvertimeResource;
use App\Http\Resources\Portal\Request\ReservationResource;

class ShowClass
{
    public function travel($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = RequestTravel::with([
            'mode',
            'expense',
            'request.comments.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id','request.comments.replies.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.type',
            'request.documents.type','request.documents.status',
            'request.dates',
            'request.detail',
            'request.user:id',
            'request.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.signatories.division',
            'request.signatories.status',
            'request.signatories.statusable',
            'request.signatories.approved.user.profile','request.signatories.approved.signatory.designationable.designation',
            'request.signatories.recommended.user.profile','request.signatories.recommended.signatory.designationable.designation',
            'request.location.region:code,name,region','request.location.province:code,name','request.location.municipality:code,name','request.location.barangay:code,name'
        ])
        ->where('request_id',$id)
        ->first();

        return new TravelResource($data);
    }

    public function overtime($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = Request::with([
            'tags.user:id',
            'tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'type',
            'dates',
            'detail',
            'user:id',
            'overtime.status',
            'user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'signatories.division',
            'signatories.status',
            'signatories.statusable',
            'signatories.approved.user.profile','signatories.approved.signatory.designationable.designation',
            'signatories.recommended.user.profile','signatories.recommended.signatory.designationable.designation'
        ])
        ->where('id',$id)
        ->first();

        return new OvertimeResource($data);
    }

    public function leave($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = RequestLeave::with([
            'detail',
            'type',
            'credits.log','credits.credit.leave',
            'request.comments.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.comments.replies.user.profile:user_id,firstname,middlename,lastname,avatar,avatar,suffix_id',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.type',
            'request.dates',
            'request.detail',
            'request.user:id',
            'request.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.signatories.division',
            'request.signatories.status',
            'request.signatories.statusable',
            'request.signatories.approved.user.profile','request.signatories.approved.signatory.designationable.designation',
            'request.signatories.recommended.user.profile','request.signatories.recommended.signatory.designationable.designation'
        ])
        ->where('request_id',$id)
        ->first();

        return new LeaveResource($data);
    }

    public function reservation($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = RequestReservation::with([
            'vehicle',
            'approved.user.profile:user_id,firstname,middlename,lastname',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar',
            'request.type',
            'request.dates',
            'request.detail',
            'request.user:id',
            'request.user.profile:user_id,firstname,middlename,lastname',
            'request.location.region:code,name,region','request.location.province:code,name','request.location.municipality:code,name','request.location.barangay:code,name',
            'request.signatories.division',
            'request.signatories.status',
            'request.signatories.statusable',
            'request.signatories.approved.user.profile','request.signatories.approved.signatory.designationable.designation',
            'request.signatories.recommended.user.profile','request.signatories.recommended.signatory.designationable.designation'
        ])
        ->where('request_id',$id)
        ->first();

        return new ReservationResource($data);
    }
}
