<?php

namespace App\Http\Controllers\Assets;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Http\Requests\Assets\VehicleRequest;
use App\Services\Assets\Vehicle\SaveClass;
use App\Services\Assets\Vehicle\ViewClass;

class VehicleController extends Controller
{
    use HandlesTransaction;

    protected $save, $view, $dropdown;

    public function __construct(SaveClass $save, ViewClass $view, DropdownClass $dropdown){
        $this->save = $save;
        $this->view = $view;
        $this->dropdown = $dropdown;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->view->lists($request);
            break;
            default:
                return inertia('Modules/Assets/Vehicles/Index',[
                    'counts' => $this->view->counts($this->dropdown->statuses('Vehicle')),
                    'dropdowns' => [
                        'stations' => $this->dropdown->stations(),
                        'types' => $this->dropdown->datas('Vehicle'),
                        'statuses' => $this->dropdown->statuses('Vehicle')
                    ]
                ]);
        }   
    }

    public function show($vehicle){
        return inertia('Modules/Assets/Vehicles/View',[
            'vehicle_data' => $this->view->view($vehicle),
            'dropdowns' => [
                'types' => $this->dropdown->datas('Vehicle'),
                'stations' => $this->dropdown->stations(),
                'statuses' => $this->dropdown->statuses('Vehicle'),
                'maintenance_types' => $this->dropdown->datas('Maintenance Type'),
                'priorities' => $this->dropdown->datas('Priority'),
                'record_statuses' => $this->dropdown->statuses('Maintenance Record'),
                'request_statuses' => $this->dropdown->statuses('Maintenance Request'),
            ],
        ]);
    }

    public function store(VehicleRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'vehicle':
                    return $this->save->store($request);
                break;
            }
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
