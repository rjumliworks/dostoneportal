<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Portal\Schedules\ViewClass;

class ScheduleController extends Controller
{
    protected ViewClass $view;

    public function __construct(ViewClass $view){
        $this->view = $view;
    }

     public function index(Request $request){
        switch($request->option){
            case 'events':
                return $this->view->events($request);
            break;
            default:
                return inertia('Modules/Portal/Schedules/Index'); 
        }   
    }
}
