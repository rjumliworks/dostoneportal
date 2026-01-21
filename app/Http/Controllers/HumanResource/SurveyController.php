<?php

namespace App\Http\Controllers\HumanResource;

use App\Services\DropdownClass;
use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\HumanResource\Survey\SaveClass;
use App\Services\HumanResource\Survey\ViewClass;

class SurveyController extends Controller
{
    use HandlesTransaction;

    public $view,$save,$dropdown;

    public function __construct(SaveClass $save, ViewClass $view, DropdownClass $dropdown){
        $this->view = $view;
        $this->save = $save;
        $this->dropdown = $dropdown;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->view->lists($request);
            break;
            case 'Question':
                return $this->view->question($request);
            break;
            case 'Station':
                return $this->view->station($request);
            break;
            case 'Division':
                return $this->view->division($request);
            break;
            case 'Unit':
                return $this->view->unit($request);
            break;
            default:
                return inertia('Modules/HumanResource/Surveys/Index',[
                    'dropdowns' => [
                        'semesters' => $this->dropdown->dropdowns('Period','Semester'),
                    ],
                    'counts' => []
                ]); 
        }   
    }

    public function store(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'survey':
                    return $this->save->store($request);
                break;
                case 'answers':
                    return $this->save->answers($request);
                break;
            }
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function show($code){
        return inertia('Modules/HumanResource/Surveys/View',[
            'survey_data' => $this->view->view($code),
            'counts' => $this->view->counts($code),
            'divisions' => $this->dropdown->dropdowns('Division')
        ]);
    }
}
