<?php

namespace App\Services\Assets\Building;

use App\Models\AssetBuilding;

class SaveClass
{
   public function store($request){
        $data = AssetBuilding::create(array_merge($request->all(), [
            'code' => $this->nextCode(),
        ]));
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

    protected function nextCode(){
        $last = AssetBuilding::where('code', 'LIKE', 'DOSTIX-BLDG-%')
            ->orderByRaw('CAST(SUBSTRING(code, 13) AS UNSIGNED) DESC')
            ->value('code');

        $next = $last ? ((int) substr($last, 12)) + 1 : 1;

        return 'DOSTIX-BLDG-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
