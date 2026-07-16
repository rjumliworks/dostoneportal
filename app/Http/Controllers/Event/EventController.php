<?php

namespace App\Http\Controllers\Event;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\Events\SaveClass;
use App\Services\Events\ViewClass;
use App\Services\Events\VenueClass;

class EventController extends Controller
{
    use HandlesTransaction;

    protected $view, $save, $dropdown, $venue;

    public function __construct(DropdownClass $dropdown, SaveClass $save, ViewClass $view, VenueClass $venue){
        $this->dropdown = $dropdown;
        $this->view = $view;
        $this->save = $save;
        $this->venue = $venue;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->view->lists($request);
            break;
            case 'search':
                return $this->view->search($request);
            break;
            default:
                return inertia('Modules/Events/Events/Index',[
                    'dropdowns' => [
                        'regions' => $this->dropdown->regions(),
                        'types' => $this->dropdown->event_list()
                    ]
                ]); 
        }   
    }

    public function store(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'event':
                    return $this->save->event($request);
                break;
                case 'venue':
                    return $this->venue->save($request);
                break;
                case 'exhibitor':
                    return $this->save->exhibitor($request);
                break;
                case 'speaker':
                    return $this->save->speaker($request);
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

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'event':
                    return $this->save->event($request);
                break;
                case 'venue':
                    return $this->venue->update($request);
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

    public function show($id){
        return inertia('Modules/Events/Events/View',[
            'event' => $this->view->view($id),
            'types' => $this->dropdown->dropdowns('Exhibit Type')
        ]);
    }
}
