<?php

namespace App\Services\Executive\Shifts;

use App\Models\Shift;
use App\Http\Resources\Executive\ShiftResource;

class ViewClass
{
    public function list($request){
        $data = Shift::with('times')
        ->paginate($request->count);
        return ShiftResource::collection($data);
    }
}
