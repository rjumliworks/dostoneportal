<?php

namespace App\Services\HumanResource\Employee;

use App\Models\User;
use App\Models\UserOrganization;
use App\Models\UserInformation;

class UpdateClass
{
    public function background($request){
        $data = UserInformation::updateOrCreate(
            ['user_id' => $request->id],
            ['backgrounds' => $request->background]
        );

        return [
            'data' => $data,
            'message' => 'Family background updated successfully',
            'info' => 'You can now manage this employee’s family background',
        ];
    }

    public function status($request){
        $data = UserOrganization::where('user_id',$request->id)->first();
        $data->status_id = $request->status_id;
        if($data->save()){
            if($request->status_id == 2){
                User::where('id',$request->id)
                    ->update([
                        'is_active' => 1,
                        'is_locked' => 0
                    ]);
            }else{
                User::where('id',$request->id)
                    ->update([
                        'is_active' => 0,
                        'is_locked' => 1
                    ]);
            }
        }
        $data->load('status');

        return [
            'data' => $data->status,
            'message' => 'Status updated successfully', 
            'info' => 'You can now manage this employee’s status',
        ];
    }

    public function shift($request){
        $data = UserOrganization::where('user_id',$request->id)->first();
        $data->shift_id = $request->shift_id;
        $data->save();
        $data->load('shift');

        return [
            'data' => $data->shift,
            'message' => 'Shift updated successfully', 
            'info' => 'You can now manage this employee’s shift',
        ];
    }
}
