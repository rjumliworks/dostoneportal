<?php

namespace App\Services\Assets\Vehicle;

use App\Models\AssetVehicle;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function lists($request){
        $data = DefaultResource::collection(
            AssetVehicle::with('station','status','driver')
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }
}
