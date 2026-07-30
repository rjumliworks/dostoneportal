<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;
use App\Services\Events\Exhibit\ViewClass;
use App\Services\Events\Exhibit\SaveClass;

class ExhibitController extends Controller
{
    use HandlesTransaction;

    protected ViewClass $view;
    protected SaveClass $save;

    public function __construct(ViewClass $view, SaveClass $save){
        $this->save = $save;
        $this->view = $view;
    }

    public function show($id){
        // return $this->view->view($id);
        return inertia('Modules/Events/Exhibits/View',[
            'exhibitor' => $this->view->view($id),
        ]);
    }

    public function store(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'exhibitor':
                    return $this->save->exhibitor($request);
                break;
                case 'contact':
                    return $this->save->contact($request);
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

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'exhibitor':
                    return $this->save->update($request);
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
}
