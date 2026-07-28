<?php

namespace App\Http\Controllers\Event;

use App\Services\DropdownClass;
use App\Traits\HandlesTransaction;
use App\Services\Events\Session\PrintClass;
use App\Services\Events\Session\ViewClass;
use App\Services\Events\Session\SaveClass;
use App\Services\Events\Session\UpdateClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Event\SessionRequest;

class SessionController extends Controller
{
     use HandlesTransaction;

    protected $view, $save, $dropdown, $update, $print;

    public function __construct(DropdownClass $dropdown, ViewClass $view, SaveClass $save, UpdateClass $update, PrintClass $print){
        $this->save = $save;
        $this->view = $view;
        $this->print = $print;
        $this->update = $update;
        $this->dropdown = $dropdown;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->view->lists($request);
            break;
            case 'csf':
                return $this->print->csf($request);
            break;
            case 'attendance':
                return $this->print->attendance($request);
            break;
            case 'participants':
                return $this->print->participants($request);
            break;
            default :
            return inertia('Modules/Events/Sessions/Index',[
                'dropdowns' => [
                    'regions' => $this->dropdown->regions()
                ]
            ]);
        }
    }

    public function show($id){
        return inertia('Modules/Events/Sessions/View',[
            'session' => $this->view->view($id),
        ]);
    }

    public function store(SessionRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'session':
                    return $this->save->session($request);
                break;
                case 'activity':
                    return $this->save->activity($request);
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
                case 'session':
                    return $this->update->session($request);
                break;
                case 'activity':
                    return $this->update->activity($request);
                break;
                case 'participant':
                    return $this->update->participant($request);
                break;
                case 'attendance':
                    return $this->update->attendance($request);
                break;
                case 'status':
                    return $this->update->status($request);
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

    public function view($key){
        return inertia('Public/Events/Session',[
            'session' => $this->view->view($key),
        ]);
    }
}
