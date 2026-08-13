<?php

namespace App\Http\Controllers\Event;

use App\Services\DropdownClass;
use App\Traits\HandlesTransaction;
use App\Services\Events\Session\PrintClass;
use App\Services\Events\Session\ViewClass;
use App\Services\Events\Session\SaveClass;
use App\Services\Events\Session\UpdateClass;
use App\Services\Events\Session\CsfClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Event\SessionRequest;

class SessionController extends Controller
{
     use HandlesTransaction;

    protected $view, $save, $dropdown, $update, $print, $csf;

    public function __construct(DropdownClass $dropdown, ViewClass $view, SaveClass $save, UpdateClass $update, PrintClass $print, CsfClass $csf){
        $this->save = $save;
        $this->view = $view;
        $this->print = $print;
        $this->update = $update;
        $this->dropdown = $dropdown;
        $this->csf = $csf;
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
            case 'attendance-excel':
                return $this->print->attendanceExcel($request);
            break;
            case 'participants':
                return $this->print->participants($request);
            break;
            case 'reservees':
                return $this->print->reservees($request);
            break;
            case 'links':
                return $this->print->links($request);
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
            'statuses' => $this->dropdown->statuses('Attendance'),
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
                case 'notify-approved':
                    return $this->update->notifyApproved($request);
                break;
                case 'approve-all':
                    return $this->update->approveAll($request);
                break;
                case 'send-certificate':
                    return $this->update->sendCertificate($request);
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
            'session' => $this->view->view($key, true),
            'statuses' => $this->dropdown->statuses('Attendance'),
        ]);
    }

    public function csf($key){
        return inertia('Public/Events/SessionCsf',[
            'session' => $this->csf->view($key),
        ]);
    }

    public function csfStore(Request $request, $key){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'affiliation' => 'required|string',
            'designation' => 'required|string',
            'comment' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'required|integer|exists:event_csf_questions,id',
            'questions.*.rating' => 'required|integer|min:1|max:5',
        ]);

        $this->csf->submit($request, $key);

        return back()->with([
            'message' => 'Thank you for your feedback!',
            'status' => true,
        ]);
    }
}
