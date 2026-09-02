<?php

namespace App\Services\Assets\Building;

use App\Models\AssetBuilding;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function lists($request){
        $data = DefaultResource::collection(
            AssetBuilding::with('station','barangay','municipality','province','region')
            ->when($request->keyword, function ($query,$keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->when($request->station, function ($query,$station) {
                $query->where('station_id',$station);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }
}
