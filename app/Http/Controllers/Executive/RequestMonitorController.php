<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\Executive\RequestMonitor\ViewClass;

class RequestMonitorController extends Controller
{
    public $view, $dropdown;

    public function __construct(ViewClass $view, DropdownClass $dropdown){
        $this->view = $view;
        $this->dropdown = $dropdown;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->view->lists($request);
            break;
            default:
                $requestTypes = $this->dropdown->datas('Request Type')->whereIn('name', [
                    'Travel Order', 'Vehicle Reservation', 'Leave Form', 'Render Overtime Service'
                ])->values();

                return inertia('Executive/RequestMonitoring/Index',[
                    'counts' => $this->view->counts($requestTypes),
                    'dropdowns' => [
                        'requests' => $requestTypes,
                    ]
                ]);
        }
    }
}
