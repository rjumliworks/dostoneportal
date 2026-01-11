<?php

namespace App\Services\Executive\Signatory;

use App\Models\OrgChart;
use App\Models\OrgSignatory;
use App\Models\OrgSignatorySchedule;

class SaveClass
{
    public function signatory($request)
    {
        if (now()->greaterThanOrEqualTo($request->start_at)) {
            $data = OrgSignatorySchedule::create($request->all());
            $signatory = OrgSignatory::find($request->signatory_id);
            $signatory->update([
                'oic_id' => $request->user_id,
                'is_oic' => 1,
            ]);
            $data->update(['is_ongoing' => 1]);
        } else {
            $data = OrgSignatorySchedule::create(
                array_merge($request->all(), ['is_ongoing' => 0])
            );
        }

        return [
            'data' => $data,
            'message' => 'Employee created successfully',
            'info' => 'You can now manage this employee’s details in the system',
        ];
    }

    public function designate($request)
    {
        if($request->is_oic){
            if(now()->greaterThanOrEqualTo($request->start_at)){
                $data = OrgSignatorySchedule::create($request->all());
                OrgSignatorySchedule::where('signatory_id', $request->signatory_id)
                ->where('id', '!=', $data->id)
                ->update(['is_ongoing' => 0]);
                $chart = OrgChart::find($request->signatory_id);
                $chart->update([
                    'oic_id' => $request->user_id,
                    'is_oic' => 1,
                ]);
                if($chart){
                    $signatory = OrgSignatory::find($request->signatory_id);
                    $signatory->update([
                        'oic_id' => $request->user_id,
                        'is_oic' => 1,
                    ]);
                }
                $data->update(['is_ongoing' => 1]);
            }else{
                $data = OrgSignatorySchedule::create(
                    array_merge($request->all(), ['is_ongoing' => 0])
                );
            }
        }else{
            $data = OrgChart::find($request->signatory_id);
            $data->update(['user_id' => $request->user_id]);
        }

        return [
            'data' => $data,
            'message' => 'Employee created successfully',
            'info' => 'You can now manage this employee’s details in the system',
        ];
    }
}
