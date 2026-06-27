<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Portal\Whereabouts\ViewClass;

class WhereaboutController extends Controller
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
                return inertia('Modules/Portal/Whereabouts/Index'); 
        }   
    }
}
