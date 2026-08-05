<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownClass;
use App\Services\Events\Participant\ViewClass;

class ParticipantController extends Controller
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
            case 'show':
                return $this->view->show($request->id);
            break;
            default:
            return inertia('Modules/Events/Participants/Index',[
                'dropdowns' => [
                    'types' => $this->dropdown->datas('Participant'),
                ],
                'counts' => $this->view->counts()
            ]);
        }
    }
}
