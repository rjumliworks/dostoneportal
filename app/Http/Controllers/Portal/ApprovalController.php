<?php

namespace App\Http\Controllers\Portal;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Services\Portal\Approval\ShowClass;
use App\Services\Portal\Approval\ViewClass;
use App\Services\Portal\Approval\SaveClass;
use App\Http\Requests\Portal\Approval\StatusRequest;

class ApprovalController extends Controller
{
    use HandlesTransaction;

    public $view,$show,$save;

    public function __construct(ViewClass $view, ShowClass $show, SaveClass $save){
        $this->view = $view;
        $this->show = $show;
        $this->save = $save;
    }

     public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->view->lists($request);
            break;
            case 'related':
                return $this->view->related($request);
            break;
            default:
                return inertia('Modules/Portal/Approvals/Index',[
                    'count' => $this->view->count()
                ]); 
        }   
    }

    public function show($string){
        $string = Crypt::decryptString($string);
        $parts = explode("krad", $string);
   
        $type = $parts[0]; 
        $code  = $parts[1]; 
        switch($type){
            case 'travel-order':
                return inertia('Modules/Portal/Approvals/View/Travels/View',[
                    'information_data' => $this->show->travel($code)
                ]);
            break;
            case 'vehicle-reservation':
                return inertia('Modules/Portal/Approvals/View/Reservations/View',[
                    'information_data' => $this->show->reservation($code)
                ]);
            break;
            case 'leave-form':
                return inertia('Modules/Portal/Approvals/View/Leaves/View',[
                    'information_data' => $this->show->leave($code)
                ]);
            break;
            case 'render-overtime-service':
                return inertia('Modules/Portal/Approvals/View/Overtime/View',[
                    'information_data' => $this->show->overtime($code)
                ]);
            break;
            case 'training':
                return inertia('Modules/Portal/Approvals/View/Training/View',[
                    'information_data' => $this->show->training($code)
                ]);
            break;
        }
    }

    public function update(StatusRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'status':
                    return $this->save->status($request);
                break;
                case 'tag':
                    return $this->save->tag($request);
                break;
            }
        });

        // Plain AJAX calls (e.g. batch-processing related signatories) don't carry
        // Inertia's request headers, so answer with JSON instead of a back() redirect
        // that would otherwise get auto-followed into an unrelated full page render.
        if (! $request->header('X-Inertia')) {
            return response()->json($result);
        }

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
