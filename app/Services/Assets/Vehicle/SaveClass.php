<?php

namespace App\Services\Assets\Vehicle;

use App\Models\AssetVehicle;

class SaveClass
{
   public function store($request){
        $data = AssetVehicle::create(array_merge($request->all(), [
            'code' => $this->nextCode(),
        ]));
        return [
            'data' => $data,
            'message' => 'Vehicle created successfully',
            'info' => 'You can now manage this vehicle’s details in the system',
        ];
    }

    protected function nextCode(){
        $last = AssetVehicle::where('code', 'LIKE', 'DOSTIX-VL-%')
            ->orderByRaw('CAST(SUBSTRING(code, 11) AS UNSIGNED) DESC')
            ->value('code');

        $next = $last ? ((int) substr($last, 10)) + 1 : 1;

        return 'DOSTIX-VL-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
