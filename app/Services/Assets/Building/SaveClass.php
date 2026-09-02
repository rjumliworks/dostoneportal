<?php

namespace App\Services\Assets\Building;

use App\Models\AssetBuilding;

class SaveClass
{
   public function store($request){
        $data = AssetBuilding::create($request->all());
        return [
            'data' => $data,
            'message' => 'Building created successfully',
            'info' => 'You can now manage this building’s details in the system',
        ];
    }

    public function update($request, AssetBuilding $building){
        $building->update($request->all());
        return [
            'data' => $building,
            'message' => 'Building updated successfully',
            'info' => 'Changes to this building have been saved',
        ];
    }
}
