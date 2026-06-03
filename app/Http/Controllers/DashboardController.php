<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dashboard\ViewClass;

class DashboardController extends Controller
{
    protected $view;

    public function __construct(
            ViewClass $view
        ){
        $this->view = $view;
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
                    ]);
            }   
           
        }
    }
}
