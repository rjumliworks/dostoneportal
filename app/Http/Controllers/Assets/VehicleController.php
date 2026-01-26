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
                    'dropdowns' => [
                        'stations' => $this->dropdown->stations()
                    ]
                ]); 
        }   
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
