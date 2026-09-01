<?php

namespace App\Services\Executive\RequestMonitor;

use App\Models\Request;
use App\Models\ListData;
use App\Http\Resources\Executive\RequestMonitor\IndexResource;

class ViewClass
{
    protected function allowedTypeIds(){
        return ListData::where('type','Request Type')
            ->whereIn('name', ['Travel Order', 'Vehicle Reservation', 'Leave Form', 'Render Overtime Service'])
            ->pluck('id');
    }

    public function counts($types){
        foreach($types as $type){
            $counts[] = Request::where('type_id',$type['value'])->count();
        }
        return $counts;
    }

    public function lists($request){
        $data = Request::with([
            'type',
            'dates',
            'detail',
            'user:id',
            'user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'tags.user:id',
            'tags.user.profile:user_id,firstname,middlename,lastname,avatar,suffix_id',
            'travel.mode',
            'leave.type',
            'reservation.vehicle',
        ])
        ->whereIn('type_id', $this->allowedTypeIds())
        ->when($request->type, fn($q, $type) => $q->where('type_id', $type))
        ->when($request->status !== null && $request->status !== '', fn($q) => $q->where('is_completed', $request->status))
        ->when($request->keyword, function ($query, $keyword) {
            $query->whereHas('user.profile', function ($q) use ($keyword) {
                $q->whereRaw('LOWER(lastname) LIKE ?', ['%' . strtolower($keyword) . '%']);
            });
        })
        ->latest()
        ->paginate($request->count ?? 10);

        return IndexResource::collection($data);
    }
}
