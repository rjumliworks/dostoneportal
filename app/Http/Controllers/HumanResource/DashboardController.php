<?php

namespace App\Http\Controllers\HumanResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\HumanResource\Dashboard\TopClass;
use App\Services\HumanResource\Dashboard\BarClass;
use App\Services\HumanResource\Dashboard\IndexClass;
use App\Services\HumanResource\Dashboard\PrintClass;

class DashboardController extends Controller
{
    protected $dashboard,$dropdown,$top,$bar,$print;

    public function __construct(IndexClass $dashboard, BarClass $bar, DropdownClass $dropdown, TopClass $top, PrintClass $print){
        $this->dashboard = $dashboard;
        $this->dropdown = $dropdown;
        $this->bar = $bar;
        $this->top = $top;
        $this->print = $print;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return [];
            break;
            case 'bar':
                return $this->bar->chart($request);
            break;
            case 'top':
                return [
                    'absences' => $this->top->absences($request),
                    'lates' => $this->top->lates($request)
                ];
            break;
            default:
               return inertia('Modules/HumanResource/Dashboard/Index',[
                    'years' => $this->dashboard->years(),
                    'counts' => $this->dashboard->counts(),
                    'employee' => $this->dashboard->employee(),
                    'divisions' => $this->dropdown->dropdowns('Division')
               ]);
        }   
    }

    public function tardiness(Request $request){
        switch($request->option){
            case 'list':
                return $this->top->tardinessReport($request);
            break;
            case 'print':
                return $this->print->tardiness($request);
            break;
            default:
                return inertia('Modules/HumanResource/Dashboard/Tardiness',[
                    'years' => $this->dashboard->years(),
                    'divisions' => $this->dropdown->dropdowns('Division')
                ]);
        }
    }

    public function absences(Request $request){
        switch($request->option){
            case 'list':
                return $this->top->absencesReport($request);
            break;
            case 'print':
                return $this->print->absences($request);
            break;
            default:
                return inertia('Modules/HumanResource/Dashboard/Absences',[
                    'years' => $this->dashboard->years(),
                    'divisions' => $this->dropdown->dropdowns('Division')
                ]);
        }
    }
}
