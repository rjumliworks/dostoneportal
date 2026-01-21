<?php

namespace App\Http\Controllers\HumanResource;

use App\Services\DropdownClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;
use App\Services\HumanResource\Credit\SaveClass;
use App\Services\HumanResource\Credit\ViewClass;

class CreditController extends Controller
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
            case 'logs':
                return $this->view->logs($request);
            break;
            default:
                return inertia('Modules/HumanResource/Credits/Index',[
                    'dropdowns' => [
                        'leaves' => $this->dropdown->leaves(),
                        'details' => $this->dropdown->dropdowns('Leave Details')
                    ]
                ]); 
        }   
    }

    public function store(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'credit':
                    return $this->save->store($request);
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
        return inertia('Modules/HumanResource/Credits/View',[
            'information_data' => $this->view->view($code),
            'dropdowns' => [
                'leaves' => $this->dropdown->leaves(),
            ],
        ]);
    }
}
