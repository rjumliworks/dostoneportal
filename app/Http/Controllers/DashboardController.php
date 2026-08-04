<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\Dashboard\ViewClass;

class DashboardController extends Controller
{
    protected $view, $dropdown;

    public function __construct(
            ViewClass $view,
            DropdownClass $dropdown 
        ){
        $this->view = $view;
        $this->dropdown = $dropdown;
    }

    public function index(Request $request){
        if(!\Auth::check()){
            return inertia('Auth/Login');
        }else{
            $user = \Auth::user();
            if($user->must_change) {
                return inertia('Auth/Activation');
            }

             switch($request->option){
                case 'whereabouts':
                    return $this->view->whereabouts();
                break;
                default:
                    return inertia('Modules/Dashboard/Index',[
                        'birthdays' => $this->view->birthdays(),
                        'dtr' => $this->view->dtr(),
                        'designations' => $this->view->designations(),
                        'attendance' => $this->view->attendance(),
                        'visibilities' => $this->dropdown->datas('Visibility'),
                        'reactions' => $this->dropdown->datas('Reaction'),
                        'types' => $this->dropdown->datas('Post Type'),
                    ]);
            }   
           
        }
    }
}
