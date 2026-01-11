<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Portal\Dtr\ViewClass;
use App\Services\Portal\Dtr\PrintClass;

class DtrController extends Controller
{
    public $view;

    public function __construct(
        ViewClass $view, PrintClass $print
    ){
        $this->view = $view;
        $this->print = $print;
    }

    public function index(Request $request){
        switch($request->option){
            case 'dtr':
                return $this->view->dtr($request);
            break;
            case 'print':
                return $this->print->dtr($request);
            break;
            default:
                return inertia('Modules/Portal/Dtr/Index'); 
        }   
    }
}
