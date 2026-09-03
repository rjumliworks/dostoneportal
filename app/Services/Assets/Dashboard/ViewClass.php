<?php

namespace App\Services\Assets\Dashboard;

use Carbon\Carbon;
use App\Models\AssetEquipment;
use App\Models\AssetVehicle;
use App\Models\AssetBuilding;
use App\Models\AssetMaintenanceRecord;
use App\Models\AssetMaintenanceRequest;
use App\Models\ListStatus;

class ViewClass
{
    public function counts(){
        $pending = ListStatus::where('classification','Maintenance Request')->where('name','Pending')->value('id');

        return [
            'equipments' => AssetEquipment::count(),
            'vehicles' => AssetVehicle::count(),
            'buildings' => AssetBuilding::count(),
            'pending_requests' => AssetMaintenanceRequest::where('status_id',$pending)->count(),
        ];
    }

    public function equipmentStatuses(){
        return $this->statusBreakdown(AssetEquipment::class, 'Equipment');
    }

    public function vehicleStatuses(){
        return $this->statusBreakdown(AssetVehicle::class, 'Vehicle');
    }

    protected function statusBreakdown($model, $classification){
        $statuses = ListStatus::where('classification',$classification)->where('is_active',1)->get();

        return $statuses->map(function ($status) use ($model) {
            return [
                'name' => $status->name,
                'bg' => $status->bg,
                'type' => $status->type,
                'count' => $model::where('status_id',$status->id)->count(),
            ];
        })->values();
    }

    public function upcomingMaintenance(){
        return AssetEquipment::with('station')
            ->whereNotNull('maintenance_due')
            ->orderBy('maintenance_due','ASC')
            ->limit(5)
            ->get()
            ->map(function ($equipment) {
                return [
                    'id' => $equipment->id,
                    'code' => $equipment->code,
                    'name' => $equipment->name,
                    'station' => $equipment->station?->name,
                    'maintenance_due' => $equipment->maintenance_due,
                    'is_overdue' => Carbon::parse($equipment->maintenance_due)->isPast(),
                ];
            });
    }

    public function equipmentSchedule(){
        $year = now()->year;

        $completedByEquipment = AssetMaintenanceRecord::where('maintainable_type','equipment')
            ->whereYear('date',$year)
            ->whereHas('status', function ($q) {
                $q->where('classification','Maintenance Record')->where('name','Completed');
            })
            ->get(['maintainable_id','date'])
            ->groupBy('maintainable_id');

        return AssetEquipment::with('type')
            ->orderBy('code','ASC')
            ->get(['id','code','name','type_id','maintenance_schedule'])
            ->map(function ($equipment) use ($completedByEquipment) {
                $completed = ($completedByEquipment->get($equipment->id) ?? collect())
                    ->map(fn ($record) => (int) date('n', strtotime($record->date)))
                    ->unique()
                    ->values()
                    ->all();

                return [
                    'id' => $equipment->id,
                    'code' => $equipment->code,
                    'name' => $equipment->name,
                    'type' => $equipment->type?->name,
                    'maintenance_schedule' => $equipment->maintenance_schedule ?: [],
                    'completed' => $completed,
                ];
            });
    }

    public function recentRecords(){
        return AssetMaintenanceRecord::with('maintainable','type','status','performer.profile')
            ->orderBy('date','DESC')
            ->limit(10)
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'date' => $record->date,
                    'operation_performed' => $record->operation_performed,
                    'type' => $record->type,
                    'status' => $record->status,
                    'performer' => $record->performer?->profile?->fullname,
                    'maintainable_type' => $record->maintainable_type,
                    'maintainable_name' => $record->maintainable?->name,
                    'maintainable_code' => $record->maintainable_type === 'equipment' ? $record->maintainable?->code : $record->maintainable_id,
                ];
            });
    }

    public function pendingRequests(){
        return AssetMaintenanceRequest::with('maintainable','requester.profile','priority','status')
            ->whereHas('status', function ($q) {
                $q->where('classification','Maintenance Request')->where('name','Pending');
            })
            ->orderBy('requested_at','DESC')
            ->limit(10)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'code' => $request->code,
                    'requested_at' => $request->requested_at,
                    'work_requested' => $request->work_requested,
                    'priority' => $request->priority,
                    'requester' => $request->requester?->profile?->fullname,
                    'maintainable_type' => $request->maintainable_type,
                    'maintainable_name' => $request->maintainable?->name,
                    'maintainable_code' => $request->maintainable_type === 'equipment' ? $request->maintainable?->code : $request->maintainable_id,
                ];
            });
    }
}
