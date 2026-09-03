<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Services\Assets\Dashboard\ViewClass;
use App\Services\DropdownClass;

class DashboardController extends Controller
{
    protected $view, $dropdown;

    public function __construct(ViewClass $view, DropdownClass $dropdown){
        $this->view = $view;
        $this->dropdown = $dropdown;
    }

    public function index(){
        return inertia('Modules/Assets/Dashboard/Index',[
            'counts' => $this->view->counts(),
            'equipment_statuses' => $this->view->equipmentStatuses(),
            'vehicle_statuses' => $this->view->vehicleStatuses(),
            'upcoming_maintenance' => $this->view->upcomingMaintenance(),
            'equipment_schedule' => $this->view->equipmentSchedule(),
            'recent_records' => $this->view->recentRecords(),
            'pending_requests' => $this->view->pendingRequests(),
            'dropdowns' => [
                'maintenance_types' => $this->dropdown->datas('Maintenance Type'),
                'record_statuses' => $this->dropdown->statuses('Maintenance Record'),
            ],
        ]);
    }
}
