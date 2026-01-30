<?php

namespace App\Http\Controllers\HumanResource;

use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\HumanResource\Visitor\SaveClass;
use App\Services\HumanResource\Visitor\ViewClass;
use App\Services\HumanResource\Visitor\RekognitionClass;

class VisitorController extends Controller
{
    use HandlesTransaction;

    public $view,$save,$dropdown,$rekognition;

    public function __construct(SaveClass $save, ViewClass $view, DropdownClass $dropdown, RekognitionClass $rekognition){
        $this->view = $view;
        $this->save = $save;
        $this->dropdown = $dropdown;
        $this->rekognition = $rekognition;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->view->lists($request);
            break;
            case 'search':
                return $this->view->search($request);
            break;
            case 'files':
                return $this->rekognition->fetch($request);
            break;
            case 'authentication-logs':
                return $this->view->logs($request);
            break;
            default:
                return inertia('Modules/HumanResource/Visitors/Index',[
                    'counts' => $this->view->counts($this->dropdown->statuses('Visitor')),
                    'dropdowns' => [
                        'types' => $this->dropdown->datas('Visitor'),
                        'statuses' => $this->dropdown->statuses('Visitor')
                    ]
                ]); 
        }   
    }

    public function store(Request $request){
        switch($request->option){
            case 'add':
                return $this->save->name($request);
            break;
            case 'file':
                return $this->rekognition->store($request);
            break;
            case 'visitor':
                $result = $this->handleTransaction(function () use ($request) {
                    return $this->save->store($request);
                });
                return back()->with([
                    'data' => $result['data'],
                    'message' => $result['message'],
                    'info' => $result['info'],
                    'status' => $result['status'],
                ]);
            break;
        }
    }

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'status':
                    return $this->save->status($request);
                break;
                case 'dtr':
                    return $this->save->dtr($request);
                break;
                case 'add':
                    return $this->save->add($request);
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
        return inertia('Modules/HumanResource/Visitors/View',[
            'user_data' => $this->view->visitor($code)
        ]);
    }

}
