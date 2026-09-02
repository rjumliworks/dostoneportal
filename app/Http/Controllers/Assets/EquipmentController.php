<?php

namespace App\Http\Controllers\Assets;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Http\Requests\Assets\EquipmentRequest;
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
}
