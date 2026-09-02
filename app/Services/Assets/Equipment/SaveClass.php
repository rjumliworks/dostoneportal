<?php

namespace App\Services\Assets\Equipment;

use App\Models\AssetEquipment;

class SaveClass
{
    public function store($request){
        $data = AssetEquipment::create([
            'code' => $this->nextCode(),
            'old_code' => $request->old_code,
            'name' => $request->name,
            'type_id' => $request->type_id,
            'maintenance_plan' => $request->maintenance_plan,
            'maintenance_due' => $request->maintenance_due,
            'remarks' => $request->remarks,
            'status_id' => $request->status_id,
            'user_id' => auth()->id(),
            'acquired_at' => $request->acquired_at,
        ]);

        $data->detail()->create([
            'brand' => $request->brand,
            'model' => $request->model,
            'price' => $request->price,
            'specification' => collect($request->specification)->filter()->values(),
        ]);

        return [
            'data' => $data,
            'message' => 'Equipment created successfully',
            'info' => 'You can now manage this equipment’s details in the system',
        ];
    }

    protected function nextCode(){
        $last = AssetEquipment::where('code', 'LIKE', 'DOSTIX-EQ-%')
            ->orderByRaw('CAST(SUBSTRING(code, 11) AS UNSIGNED) DESC')
            ->value('code');

        $next = $last ? ((int) substr($last, 10)) + 1 : 1;

        return 'DOSTIX-EQ-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
