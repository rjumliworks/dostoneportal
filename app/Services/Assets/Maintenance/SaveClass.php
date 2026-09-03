<?php

namespace App\Services\Assets\Maintenance;

use App\Models\AssetMaintenanceRecord;
use App\Models\AssetMaintenanceRequest;
use App\Models\ListStatus;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Storage;

class SaveClass
{
    protected function resolveMaintainable($type, $id){
        $class = Relation::getMorphedModel($type);
        abort_unless($class, 404);
        return $class::findOrFail($id);
    }

    public function storeRecord($request){
        $maintainable = $this->resolveMaintainable($request->maintainable_type, $request->maintainable_id);

        $data = AssetMaintenanceRecord::create([
            'maintainable_type' => $request->maintainable_type,
            'maintainable_id' => $request->maintainable_id,
            'request_id' => $request->request_id,
            'type_id' => $request->type_id,
            'status_id' => $request->status_id,
            'date' => $request->date,
            'operation_performed' => $request->operation_performed,
            'remarks' => $request->remarks,
            'performed_by' => $request->performed_by,
            'cost' => $request->cost,
            'attachment' => $request->hasFile('attachment') ? $request->file('attachment')->store('maintenance/records', 'public') : null,
            'next_due' => $request->next_due,
        ]);

        if($request->request_id){
            $completed = ListStatus::where('classification','Maintenance Request')->where('name','Completed')->value('id');
            AssetMaintenanceRequest::where('id',$request->request_id)->update(['status_id' => $completed]);
        }

        if($request->next_due && in_array('maintenance_due', $maintainable->getFillable())){
            $maintainable->update(['maintenance_due' => $request->next_due]);
        }

        return [
            'data' => [
                'record' => $data->load('type','status','performer.profile','request.requester.profile'),
                'request' => $request->request_id ? AssetMaintenanceRequest::with('requester.profile','priority','status','record')->find($request->request_id) : null,
            ],
            'message' => 'Maintenance record added successfully',
            'info' => 'The maintenance history has been updated',
        ];
    }

    public function updateRecord($request, AssetMaintenanceRecord $record){
        $attachment = $record->attachment;
        if($request->hasFile('attachment')){
            if($attachment){
                Storage::disk('public')->delete($attachment);
            }
            $attachment = $request->file('attachment')->store('maintenance/records', 'public');
        }

        $record->update([
            'type_id' => $request->type_id,
            'status_id' => $request->status_id,
            'date' => $request->date,
            'operation_performed' => $request->operation_performed,
            'remarks' => $request->remarks,
            'performed_by' => $request->performed_by,
            'cost' => $request->cost,
            'attachment' => $attachment,
            'next_due' => $request->next_due,
        ]);

        if($request->next_due){
            $maintainable = $record->maintainable;
            if($maintainable && in_array('maintenance_due', $maintainable->getFillable())){
                $maintainable->update(['maintenance_due' => $request->next_due]);
            }
        }

        return [
            'data' => [
                'record' => $record->load('type','status','performer.profile','request.requester.profile'),
                'request' => $record->request_id ? AssetMaintenanceRequest::with('requester.profile','priority','status','record')->find($record->request_id) : null,
            ],
            'message' => 'Maintenance record updated successfully',
            'info' => 'Changes to this maintenance record have been saved',
        ];
    }

    public function destroyRecord(AssetMaintenanceRecord $record){
        if($record->attachment){
            Storage::disk('public')->delete($record->attachment);
        }
        $record->delete();

        return [
            'data' => ['id' => $record->id],
            'message' => 'Maintenance record deleted successfully',
            'info' => 'The record has been removed from the maintenance history',
        ];
    }

    public function storeMaintenanceRequest($request){
        $maintainable = $this->resolveMaintainable($request->maintainable_type, $request->maintainable_id);
        $pending = ListStatus::where('classification','Maintenance Request')->where('name','Pending')->value('id');

        $data = AssetMaintenanceRequest::create([
            'code' => $this->nextRequestCode(),
            'maintainable_type' => $request->maintainable_type,
            'maintainable_id' => $request->maintainable_id,
            'requested_by' => $request->requested_by,
            'location' => $request->location ?: $maintainable->station?->name,
            'work_requested' => $request->work_requested,
            'problem_description' => $request->problem_description,
            'priority_id' => $request->priority_id,
            'status_id' => $pending,
            'requested_at' => $request->requested_at,
        ]);

        return [
            'data' => $data->load('requester.profile','priority','status','record'),
            'message' => 'Maintenance request submitted successfully',
            'info' => 'The request will be reviewed by the asset management officer',
        ];
    }

    public function updateMaintenanceRequest($request, AssetMaintenanceRequest $maintenanceRequest){
        $maintenanceRequest->update([
            'location' => $request->location,
            'work_requested' => $request->work_requested,
            'problem_description' => $request->problem_description,
            'priority_id' => $request->priority_id,
            'status_id' => $request->status_id ?: $maintenanceRequest->status_id,
            'remarks' => $request->remarks,
            'requested_at' => $request->requested_at,
        ]);

        return [
            'data' => $maintenanceRequest->load('requester.profile','priority','status','record'),
            'message' => 'Maintenance request updated successfully',
            'info' => 'Changes to this request have been saved',
        ];
    }

    public function destroyMaintenanceRequest(AssetMaintenanceRequest $maintenanceRequest){
        if($maintenanceRequest->record){
            $maintenanceRequest->record()->update(['request_id' => null]);
        }
        $maintenanceRequest->delete();

        return [
            'data' => ['id' => $maintenanceRequest->id],
            'message' => 'Maintenance request deleted successfully',
            'info' => 'The request has been removed',
        ];
    }

    protected function nextRequestCode(){
        $last = AssetMaintenanceRequest::where('code', 'LIKE', 'DOSTIX-MR-%')
            ->orderByRaw('CAST(SUBSTRING(code, 11) AS UNSIGNED) DESC')
            ->value('code');

        $next = $last ? ((int) substr($last, 10)) + 1 : 1;

        return 'DOSTIX-MR-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
