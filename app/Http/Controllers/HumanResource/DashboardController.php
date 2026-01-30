<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\HumanResource\Dashboard\IndexClass;

class DashboardController extends Controller
{
    protected $dashboard,$dropdown;

    public function __construct(IndexClass $dashboard, DropdownClass $dropdown){
        $this->dashboard = $dashboard;
        $this->dropdown = $dropdown;
    }

     public function index(Request $request){
        switch($request->option){
            case 'lists':
                return [];
            break;
            default:
               return inertia('Modules/HumanResource/Dashboard/Index',[
                    'counts' => $this->dashboard->counts(),
                    'employee' => $this->dashboard->employee(),
                    'divisions' => $this->dropdown->dropdowns('Division')
               ]);
        }   
    }
}
