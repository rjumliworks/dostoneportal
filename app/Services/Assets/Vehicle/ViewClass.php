<?php

namespace App\Services\Assets\Vehicle;

use App\Models\AssetVehicle;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function counts($types){
        foreach($types as $type){
            $counts[] = AssetVehicle::where('status_id',$type['value'])->count();
        }
        return $counts;
    }

    public function view($id){
        $data = new DefaultResource(
            AssetVehicle::with('type','status','station','driver.profile')
            ->with(['records' => function ($q) {
                $q->orderBy('date','DESC');
            }, 'records.type', 'records.status', 'records.performer.profile', 'records.request.requester.profile'])
            ->with(['maintenanceRequests' => function ($q) {
                $q->orderBy('requested_at','DESC');
            }, 'maintenanceRequests.requester.profile', 'maintenanceRequests.priority', 'maintenanceRequests.status', 'maintenanceRequests.record'])
            ->findOrFail($id)
        );
        return $data;
    }

    public function lists($request){
        $data = DefaultResource::collection(
            AssetVehicle::with('station','status','driver','type')
            ->when($request->keyword, function ($query,$keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->when($request->type, function ($query,$type) {
                $query->where('type_id',$type);
            })
            ->when($request->status, function ($query,$status) {
                $query->where('status_id',$status);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }
}
