<?php

namespace App\Http\Controllers\Trace;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\Trace\Event\SaveClass;
use App\Services\Trace\Event\ViewClass;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Trace\EventRequest;

class EventController extends Controller
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
            case 'list':
                return $this->view->lists($request);
            break;
            case 'search':
                return $this->view->search($request);
            break;
            default:
                return inertia('Modules/Trace/Events/Index',[
                    'dropdowns' => [
                        'regions' => $this->dropdown->regions(),
                        'types' => $this->dropdown->datas('Event'),
                        'modes' => $this->dropdown->datas('Event Mode'),
                        'audiences' => $this->dropdown->datas('Audience')
                    ],
                    'counts' => $this->view->counts($this->dropdown->datas('Audience'))
                ]); 
        }   
    }

    public function store(EventRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'event':
                    return $this->save->store($request);
                break;
                case 'participant':
                    return $this->save->participant($request);
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

    public function show($string){
        $string = Crypt::decryptString($string);
        $parts = explode("krad", $string);
   
        $type = $parts[0]; 
        $code  = $parts[1]; 

        return inertia('Modules/Trace/Events/View',[
            'information_data' => $this->view->event($code),
        ]);
    }
}
