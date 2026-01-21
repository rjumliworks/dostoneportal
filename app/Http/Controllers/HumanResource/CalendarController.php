<?php

namespace App\Http\Controllers\HumanResource;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\HumanResource\Calendar\SaveClass;
use App\Services\HumanResource\Calendar\ViewClass;
use App\Http\Requests\HumanResource\CalendarRequest;

class CalendarController extends Controller
{
    use HandlesTransaction;

    protected $view, $save, $dropdown;

    public function __construct(DropdownClass $dropdown, SaveClass $save, ViewClass $view){
        $this->dropdown = $dropdown;
        $this->view = $view;
        $this->save = $save;
    }

    public function index(Request $request){
        switch($request->option){
            case 'events':
                return $this->view->events($request);
            break;
            default :
            return inertia('Modules/HumanResource/Calendar/Index',[
                'dropdowns' => [
                    'events' => $this->dropdown->events(),
                    'stations' => $this->dropdown->stations()
                ]
            ]);
        }
    }

    public function store(CalendarRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->save($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->update($request);
        });
        
        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function destroy($id)
    {
        $result = $this->handleTransaction(function () use ($id) {
            return $this->save->delete($id);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
