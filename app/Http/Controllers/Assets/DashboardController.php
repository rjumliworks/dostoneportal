<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Services\Assets\Dashboard\ViewClass;

class DashboardController extends Controller
{
    protected $view;

    public function __construct(ViewClass $view){
        $this->view = $view;
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
        ]);
    }
}
