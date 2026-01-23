<?php

namespace App\Services\Portal\Request;

use App\Models\RequestOvertime;

class SaveClass
{
    public function overtime($request){
        $data = RequestOvertime::find($request->id);
        $data->targets = $request->targets;
        $data->save();

        return [
            'data' => $data,
            'message' => 'Overtime targets updated',
            'info' => "The targets has been successfully updated."
        ];
    }
}
