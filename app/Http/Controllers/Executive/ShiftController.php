<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Executive\Shifts\ViewClass;


class ShiftController extends Controller
{
    protected ViewClass $view;

    public function __construct(ViewClass $view){
        $this->view = $view;
    }

     public function index(Request $request){
  
        switch($request->option){
            case 'list':
                return $this->view->list($request);
            break;
            default:
            return inertia('Executive/Shifts/Index'); 
        }   
    }
}
