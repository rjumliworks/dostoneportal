<?php

namespace App\Services\Portal\Request;

use App\Models\Request;
use App\Models\RequestSignatory;
use App\Models\ListLeave;
use App\Models\UserCredit;
use App\Http\Resources\Portal\Request\IndexResource;

class ViewClass
{
    public function counts($types){
        $user_id = \Auth::user()->id;
        foreach($types as $type){
            $counts[] = Request::whereHas('tags', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->where('type_id',$type['value'])->count();
        }
        return $counts;
    }

    public function lists($request){
        $user_id = \Auth::user()->id;
        $division_id = \Auth::user()->organization->division_id;
        $data = RequestSignatory::with([
            'status',
            'request.tags.user:id',
            'request.tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'request.type',
            'request.dates',
            'request.detail',
            // 'leave:id,request_id,type_id',
            // 'leave.type:id,name',
        ])
        ->when($request->status, fn($q, $status) => $q->where('status_id', $status))
        ->when($request->type, fn($q, $type                                                                                                                                                                                                                                                                                                                                                                                                             ) =>
            $q->whereHas('request', function ($query) use ($type) {
                $query->where('type_id', $type);
            })
        )
        ->when($request->keyword, function ($query, $keyword) {
            $query->whereHas('request.user.profile', function ($q) use ($keyword) {
                $q->whereRaw('LOWER(lastname) LIKE ?', ['%' . strtolower($keyword) . '%']);
            });
        })
        ->whereHas('request.tags', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->whereIn('division_id',[$division_id,48])
        ->latest() 
        ->paginate($request->count ?? 10);

        return IndexResource::collection($data);
    }

    public function credits()
    {
        $year = now()->year;
        $user_id = \Auth::user()->id;
        $is_regular = \Auth::user()->organization->type_id == 15;
        $sex = \Auth::user()->profile->sex;
        $options = [];

        if($is_regular){
            $options = collect();
            $leaves = ListLeave::where('is_regular',1)->where('is_active',1)->where('is_requested',0)->get();
            foreach($leaves as $leave){
                $item = UserCredit::with('leave')->where('leave_id',$leave->id)->where('user_id',$user_id)->where('is_active',1)->where('year',$year)->first();
                if($item){
                    $options[] = [
                        'label' => 'Require Credits',
                        'options' => [
                            'value' => $item->id,
                            'type_id' => $item->leave->id,
                            'label' => $item->leave->name.' - '.$item->balance,
                            'name' => $item->leave->name,
                            'citation' => $item->leave->citation,
                            'is_regular' => $item->leave->is_regular,
                            'is_after' => $item->leave->is_after,
                            'type' => $item->leave->type,
                            'others' => $item->leave->others,
                            'balance' => $item->balance,
                            'disabled'   => ($item->balance == 0 || $item->balance == 0.00),
                            'required_credits' => true,
                            'required_document' =>  $item->leave->requires_document
                        ]
                    ];
                }
            }
            $leaves = ListLeave::where(function ($query) use ($sex){
                $query->whereNull('sex')->orWhere('sex',$sex->name);
            })
            ->where('is_regular',1)->where('is_active',1)->where('is_requested',1)->get();
            foreach($leaves as $item){
                $options->push([
                    'label' => 'Require Documents',
                    'options' => [
                        'value' => $item->id,
                        'type_id' => $item->id,
                        'label' => $item->name,
                        'name' => $item->name,
                        'citation' => $item->citation,
                        'is_regular' => $item->is_regular,
                        'is_after' => $item->is_after,
                        'type' => $item->type,
                        'others' => $item->others,
                        'max_days' => $item->max_days,
                        'renewal' => $item->renewal_period,
                        'required_credits' => false,
                        'required_document' =>  $item->requires_document
                    ]
                ]);
            }
        }else{
            $options = collect();
            $item = UserCredit::with('leave')
            ->where('leave_id', 14)
            ->where('user_id', $user_id)
            ->where('is_active', 1)
            ->where('year', $year)
            ->first();

            if ($item) {
                $options->push([
                    'label' => 'Require Credits',
                    'options' => [
                        'value' => $item->id,
                        'type_id' => $item->leave->id,
                        'label' => $item->leave->name.' - '.$item->balance,
                        'name' => $item->leave->name,
                        'citation' => $item->leave->citation,
                        'is_regular' => $item->leave->is_regular,
                        'is_after' => $item->leave->is_after,
                        'type' => $item->leave->type,
                        'others' => $item->leave->others,
                        'balance' => $item->balance,
                        'disabled'   => ($item->balance == 0 || $item->balance == 0.00),
                        'required_credits' => true,
                        'required_document' => false
                    ]
                ]);
            }

            $leaves = ListLeave::where(function ($query) use ($sex){
                $query->whereNull('sex')->orWhere('sex',$sex->name);
            })
            ->where('is_nonregular',1)->where('is_active',1)->get();
            foreach($leaves as $item){
                $options->push([
                    'label' => 'Require Documents',
                    'options' => [
                        'value' => $item->id,
                        'type_id' => $item->id,
                        'label' => $item->name,
                        'name' => $item->name,
                        'citation' => $item->citation,
                        'is_regular' => $item->is_regular,
                        'is_after' => $item->is_after,
                        'type' => $item->type,
                        'others' => $item->others,
                        'max_days' => $item->max_days,
                        'renewal' => $item->renewal_period,
                        'required_credits' => false,
                        'required_document' =>  $item->requires_document
                    ]
                ]);
            }

            $item = ListLeave::where('id', 16)
            ->where('is_active', 1)
            ->first();

            if ($item) {
                $options->push([
                    'label' => 'Others',
                    'options' => [
                        'value' => $item->id,
                        'type_id' => $item->id,
                        'label' => $item->name,
                        'name' => $item->name,
                        'citation' => $item->citation,
                        'is_regular' => $item->is_regular,
                        'is_after' => $item->is_after,
                        'type' => $item->type,
                        'others' => $item->others,
                        'required_credits' => false,
                        'required_document' => false
                    ]
                ]);
            }
        }

        $grouped = $options->groupBy('label')->map(function ($items) {
            return [
                'label' => $items->first()['label'],
                'options' => $items->pluck('options')->values()
            ];
        })->values();

        return $grouped;
    }
}
