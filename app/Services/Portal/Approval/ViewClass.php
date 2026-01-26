<?php

namespace App\Services\Portal\Approval;

use App\Models\Request;
use App\Models\RequestSignatory;
use App\Models\OrgSignatory;
use App\Http\Resources\Portal\Approval\IndexResource;

class ViewClass
{
    public function lists($request){
        $signatories = OrgSignatory::with('designationable')->where('user_id',\Auth::user()->id)->orWhere('oic_id',\Auth::user()->id)->where('is_active',1)->get(); 
        
        $hasRecommendationRole = $signatories->contains(fn($s) => $s->designationable->designation_id == 44);
        $hasApprovalRole = $signatories->contains(fn($s) => $s->designationable->designation_id == 43);
        $hasHRMORole = $signatories->contains(fn($s) => $s->designationable->designation_id == 48);
        
        $status = $request->status ?? ($hasRecommendationRole || $hasHRMORole ? 24 : 25);
        // $status = $request->status ?? (($signatory['designationable']['designation_id'] == 44) ? 24 : 25);
        $data = RequestSignatory::with([
            'status',
            'statusable',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.type',
            'request.dates',
            'request.detail'
        ])
        ->when($request->type, fn($q, $type) => $q->where('type_id', $type))
        ->when($request->keyword, function ($query, $keyword) {
            $query->whereHas('user.profile', function ($q) use ($keyword) {
                $q->whereRaw('LOWER(lastname) LIKE ?', ['%' . strtolower($keyword) . '%']);
            });
        })
        ->when(empty($request->status), fn($q) => $q->where('status_id', $status))
        ->when(true, function ($query) use ($signatories, $request, $hasApprovalRole, $hasRecommendationRole, $hasHRMORole) {
            if ($request->status) {
                if ($request->status == 26) {
                    $query->whereHas('approved', function ($q){
                        $q->where('user_id', \Auth::user()->id);
                    });
                }elseif($request->status == 25){
                    $query->whereHas('recommended', function ($q){
                        $q->where('user_id', \Auth::user()->id);
                    });
                }else{
                    $query->whereHas('disapproved', function ($q){
                        $q->where('user_id', \Auth::user()->id);
                    })->where('is_disapproved',1);
                }
            } else {
                // if ($signatory['designationable']['designation_id'] == 44) {
                //     $query->where('division_id', $signatory['designationable']['assigned_id'])
                //           ->where('is_approval_only', 0);
                // }
                $query->where(function ($q) use ($signatories, $hasRecommendationRole, $hasApprovalRole, $hasHRMORole) {
                    // 🔹 HRMO recommending role (designation 48)
                    if ($hasHRMORole) {
                        $q->orWhere(function ($sub) {
                            $sub->where('division_id', 48)
                                ->where('is_approval_only', 0);
                        });
                    }
                    // 🔹 Recommending role (designation 44)
                    if ($hasRecommendationRole) {
                        $divisionIds = $signatories
                            ->where('designationable.designation_id', 44)
                            ->pluck('designationable.assigned_id');

                        $q->orWhere(function ($sub) use ($divisionIds) {
                            $sub->whereIn('division_id', $divisionIds)
                                ->where('is_approval_only', 0);
                        });
                    }
                });
            }
        })
        ->orderBy('created_at',($request->status) ? 'DESC' : 'ASC')
        ->paginate($request->count ?? 10);

        return IndexResource::collection($data);
    }

    public function count(){
        return [
            Request::whereHas('signatories', function ($query) { $query->whereHas('recommended', function ($q){
                $q->where('user_id', \Auth::user()->id);
            }); })->count(),
            Request::whereHas('signatories', function ($query) { $query->whereHas('approved', function ($q){
                $q->where('user_id', \Auth::user()->id);
            }); })->count(),
            Request::whereHas('signatories', function ($query) { $query->whereHas('disapproved', function ($q){
                $q->where('user_id', \Auth::user()->id);
            }); })->count(),
        ];
    }
}
