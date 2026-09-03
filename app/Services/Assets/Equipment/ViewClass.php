<?php

namespace App\Services\Assets\Equipment;

use App\Models\AssetEquipment;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function counts($statuses){
        foreach($statuses as $status){
            $counts[] = AssetEquipment::where('status_id',$status['value'])->count();
        }
        return $counts;
    }

    public function view($code){
        $data = new DefaultResource(
            AssetEquipment::with('type','station','status','detail','currentAssignment.user.profile')
            ->with(['records' => function ($q) {
                $q->orderBy('date','DESC');
            }, 'records.type', 'records.status', 'records.performer.profile', 'records.request.requester.profile'])
            ->with(['maintenanceRequests' => function ($q) {
                $q->orderBy('requested_at','DESC');
            }, 'maintenanceRequests.requester.profile', 'maintenanceRequests.priority', 'maintenanceRequests.status', 'maintenanceRequests.record'])
            ->where('code',$code)->firstOrFail()
        );
        return $data;
    }

    public function lists($request){
        $data = DefaultResource::collection(
            AssetEquipment::with('type','status','station','detail','currentAssignment.user.profile')
            ->when($request->keyword, function ($query,$keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('code', 'LIKE', "%{$keyword}%")
                    ->orWhere('old_code', 'LIKE', "%{$keyword}%");
            })
            ->when($request->type, function ($query,$type) {
                $query->where('type_id',$type);
            })
            ->when($request->station, function ($query,$station) {
                $query->where('station_id',$station);
            })
            ->when($request->status, function ($query,$status) {
                $query->where('status_id',$status);
            })
            ->orderBy('code', 'ASC')
            ->paginate($request->count)
        );
        return $data;
    }
}
