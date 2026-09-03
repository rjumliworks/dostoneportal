<?php

namespace App\Http\Controllers\Assets;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use App\Models\AssetMaintenanceRecord;
use App\Models\AssetMaintenanceRequest;
use App\Http\Requests\Assets\MaintenanceRecordRequest;
use App\Http\Requests\Assets\MaintenanceRequestRequest;
use App\Services\Assets\Maintenance\SaveClass;
use App\Services\Assets\Maintenance\PrintClass;

class MaintenanceController extends Controller
{
    use HandlesTransaction;

    protected $save, $print;

    public function __construct(SaveClass $save, PrintClass $print){
        $this->save = $save;
        $this->print = $print;
    }

    public function printRecords($type, $id){
        return $this->print->records($type, $id);
    }

    public function storeRecord(MaintenanceRecordRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->storeRecord($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function updateRecord(MaintenanceRecordRequest $request, AssetMaintenanceRecord $record){
        $result = $this->handleTransaction(function () use ($request, $record) {
            return $this->save->updateRecord($request, $record);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function destroyRecord(AssetMaintenanceRecord $record){
        $result = $this->handleTransaction(function () use ($record) {
            return $this->save->destroyRecord($record);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function storeMaintenanceRequest(MaintenanceRequestRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->storeMaintenanceRequest($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function updateMaintenanceRequest(MaintenanceRequestRequest $request, AssetMaintenanceRequest $maintenanceRequest){
        $result = $this->handleTransaction(function () use ($request, $maintenanceRequest) {
            return $this->save->updateMaintenanceRequest($request, $maintenanceRequest);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function destroyMaintenanceRequest(AssetMaintenanceRequest $maintenanceRequest){
        $result = $this->handleTransaction(function () use ($maintenanceRequest) {
            return $this->save->destroyMaintenanceRequest($maintenanceRequest);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
