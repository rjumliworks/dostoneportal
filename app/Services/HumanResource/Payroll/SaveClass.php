<?php

namespace App\Services\HumanResource\Payroll;

use App\Models\Payroll;
use App\Http\Resources\HumanResource\Payroll\Regular\PayrollResource;

class SaveClass
{
    public function remove($request){
        $payroll = Payroll::findOrFail($request->id);
        $payroll->delete();

        return [
            'data' => '',
            'message' => 'Payroll was remove successful!', 
            'info' => "You've successfully removed a payroll."
        ];
    }

    public function deduction($request){
        $data = Payroll::where('id',$request->payroll_id)->first();
        $deduction = $data->deductions()->create($request->all());
        if($deduction){
            $data->deduction = floatval(str_replace(['₱', ','], '', $data->deduction)) + floatval(str_replace(['₱', ','], '', $request->amount));
            $data->netpay = floatval(str_replace(['₱', ','], '',$data->gross)) - floatval(str_replace(['₱', ','], '', $data->deduction));
            $data->save();
        }
        $data = Payroll::with('deductions.deduction')
        ->with('user.profile:id,user_id,firstname,middlename,lastname,suffix_id')
        ->with('user:id,username','user.organization:id,user_id,position_id,salary_id,type_id','user.organization.type:id,name','user.organization.position:id,name','user.organization.salary:id,grade,amount')
        ->where('id',$request->payroll_id)->first();
        return [
            'data' => new PayrollResource($data),
            'message' => 'Cycle creation was successful!', 
            'info' => "You've successfully created a new cycle."
        ];
    }


    // public function deduction($request){
    //     $data = PayrollDeduction::where('id',$request->id)->first();
    //     $data->amount = $request->amount;
    //     $data->save();
    //     return [
    //         'data' => $data,
    //         'message' => 'Deduction added successfully!', 
    //         'info' => "You've successfully created a new cycle."
    //     ];
    // }
}
