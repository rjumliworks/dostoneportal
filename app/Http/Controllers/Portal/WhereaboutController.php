<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\Portal\Whereabouts\ViewClass;

class WhereaboutController extends Controller
{
    protected ViewClass $view;
    protected DropdownClass $dropdown;

    public function __construct(ViewClass $view, DropdownClass $dropdown){
        $this->view = $view;
        $this->dropdown = $dropdown;
    }

    public function index(Request $request){
        switch($request->option){
            case 'list':
                return $this->view->list($request);
            break;
            default:
                return inertia('Modules/Portal/Whereabouts/Index',[
                    'dropdowns' => [
                        'divisions' => $this->view->dropdowns(),
                        'stations' => $this->dropdown->stations(),
                        'units' => $this->dropdown->units(null),
                        'positions' => $this->dropdown->positions(),
                        'shifts' => $this->dropdown->shifts(),
                        'statuses' => $this->dropdown->statuses('Status'),
                        'employment_statuses' => $this->dropdown->datas('Employment Status')
                    ]
                ]); 
        }   
    }
}
