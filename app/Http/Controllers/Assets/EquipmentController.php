<?php

namespace App\Http\Controllers\Assets;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Models\AssetEquipment;
use App\Http\Requests\Assets\EquipmentRequest;
use App\Http\Requests\Assets\AssignEquipmentUserRequest;
use App\Services\Assets\Equipment\SaveClass;
use App\Services\Assets\Equipment\ViewClass;

class EquipmentController extends Controller
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
                return inertia('Modules/Assets/Equipments/Index',[
                    'counts' => $this->view->counts($this->dropdown->statuses('Equipment')),
                    'dropdowns' => [
                        'types' => $this->dropdown->datas('Asset'),
                        'stations' => $this->dropdown->stations(),
                        'statuses' => $this->dropdown->statuses('Equipment'),
                    ]
                ]);
        }
    }

    public function store(EquipmentRequest $request){
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

    public function update(EquipmentRequest $request, AssetEquipment $equipment){
        $result = $this->handleTransaction(function () use ($request, $equipment) {
            return $this->save->update($request, $equipment);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function assign(AssignEquipmentUserRequest $request, AssetEquipment $equipment){
        $result = $this->handleTransaction(function () use ($request, $equipment) {
            return $this->save->assign($request, $equipment);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
