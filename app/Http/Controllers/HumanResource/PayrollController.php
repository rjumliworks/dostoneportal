<?php

namespace App\Http\Controllers\HumanResource;

use App\Traits\HandlesTransaction;
use App\Services\DropdownClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\HumanResource\PayrollRequest;
use App\Services\HumanResource\Payroll\ViewClass;
use App\Services\HumanResource\Payroll\SaveClass;
use App\Services\HumanResource\Payroll\RegularClass;
use App\Services\HumanResource\Payroll\ContractualClass;

class PayrollController extends Controller
{
    use HandlesTransaction;

    protected $dropdown, $regular, $contractual, $save, $view;

    public function __construct(DropdownClass $dropdown, RegularClass $regular, ContractualClass $contractual, SaveClass $save, ViewClass $view){
        $this->save = $save;
        $this->view = $view;
        $this->regular = $regular;
        $this->contractual = $contractual;
        $this->dropdown = $dropdown;
    }

    public function index(Request $request){
        switch($request->option){
            case 'regular':
                return $this->regular->lists($request);
            break;
            case 'contractual':
                return $this->contractual->lists($request);
            break;
            case 'search':
                if($request->is_regular){
                    return $this->regular->search($request);
                }else{
                    return $this->contractual->search($request);
                }
            break;
            case 'print':
                return $this->view->print($request);
            break;
            default:
                return inertia('Modules/HumanResource/Payroll/Index',[
                    'dropdowns' => [
                        'payrolls' => $this->dropdown->dropdowns('Payroll')
                    ]
                ]); 
        }   
    }

    public function show($code){
        switch($code){
            case 'regular':
                return inertia('Modules/HumanResource/Payroll/Regular/Index',[
                    'dropdowns' => [
                        'payrolls' => $this->dropdown->dropdowns('Payroll','Regular')
                    ]
                ]);
            break;
            case 'contractual':
                return inertia('Modules/HumanResource/Payroll/Contractual/Index',[
                    'dropdowns' => [
                        'payrolls' => $this->dropdown->dropdowns('Payroll','Regular')
                    ]
                ]);
            break;
        }
    }

    public function view($type,$code){
        switch($type){
            case 'regular':
                return inertia('Modules/HumanResource/Payroll/Regular/View',[
                    'payroll_data' => $this->regular->view($code),
                    'dropdowns' => [
                        'deductions' => $this->dropdown->deductions()
                    ]
                ]);
            break;
            case 'contractual':
                return inertia('Modules/HumanResource/Payroll/Contractual/View',[
                    'payroll_data' => $this->contractual->view($code),
                    'dropdowns' => [
                        'deductions' => $this->dropdown->deductions()
                    ]
                ]);
            break;
        }
    }

    public function store(PayrollRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'regular':
                    return $this->regular->cycle($request);
                break;
                case 'contractual':
                    return $this->contractual->cycle($request);
                break;
                case 'payroll':
                    if($request->is_regular){
                        return $this->regular->payroll($request);
                    }else{
                        return $this->contractual->payroll($request);
                    }
                break;
                case 'remove':
                    return $this->save->remove($request);
                break;
                case 'deduction':
                    return $this->save->deduction($request);
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
                case 'deduction':
                    return $this->save->deduction($request);
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
