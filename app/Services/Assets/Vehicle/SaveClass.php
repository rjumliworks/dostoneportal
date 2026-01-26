<?php

namespace App\Services\Assets\Vehicle;

use App\Models\AssetVehicle;

class SaveClass
{
   public function store($request){
        $data = AssetVehicle::create($request->all());
        return [
            'data' => $data,
            'message' => 'Employee created successfully', 
            'info' => 'You can now manage this employee’s details in the system',
        ];
    }
}
