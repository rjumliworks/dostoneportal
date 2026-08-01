<?php

namespace App\Http\Controllers\HumanResource;

use App\Models\Survey;
use App\Services\DropdownClass;
use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
            case 'print':
                return $this->view->print($request->id);
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
        if($request->option === 'survey'){
            $request->validate([
                'year' => ['required', 'digits:4'],
                'semester_id' => ['required', 'exists:list_dropdowns,id'],
            ]);

            if(Survey::where('year', $request->year)->where('semester_id', $request->semester_id)->exists()){
                throw ValidationException::withMessages([
                    'year' => 'A survey for this year and semester already exists.',
                ]);
            }
        }

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
