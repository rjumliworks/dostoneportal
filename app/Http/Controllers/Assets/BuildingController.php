<?php

namespace App\Http\Controllers\Assets;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Models\AssetBuilding;
use App\Http\Requests\Assets\BuildingRequest;
use App\Services\Assets\Building\SaveClass;
use App\Services\Assets\Building\ViewClass;

class BuildingController extends Controller
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
                return inertia('Modules/Assets/Buildings/Index',[
                    'dropdowns' => [
                        'stations' => $this->dropdown->stations(),
                        'regions' => $this->dropdown->regions(),
                    ]
                ]);
        }
    }

    public function show(AssetBuilding $building){
        return inertia('Modules/Assets/Buildings/View',[
            'building_data' => $this->view->view($building->id),
            'dropdowns' => [
                'stations' => $this->dropdown->stations(),
                'regions' => $this->dropdown->regions(),
                'maintenance_types' => $this->dropdown->datas('Maintenance Type'),
                'priorities' => $this->dropdown->datas('Priority'),
                'record_statuses' => $this->dropdown->statuses('Maintenance Record'),
                'request_statuses' => $this->dropdown->statuses('Maintenance Request'),
            ],
        ]);
    }

    public function store(BuildingRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->store($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function update(BuildingRequest $request, AssetBuilding $building){
        $result = $this->handleTransaction(function () use ($request, $building) {
            return $this->save->update($request, $building);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
