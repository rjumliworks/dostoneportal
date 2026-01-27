<?php

namespace App\Services\Portal\Request;

use Hashids\Hashids;
use App\Models\Request;
use App\Models\RequestOvertime;
use App\Models\RequestDocument;

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

    public function document($request){
        $hashids = new Hashids('krad', 10);
        $id = $hashids->decode($request->request_id);

        $data = Request::findOrFail($id[0]);

        $data->documents()->create([
            'data' => $request->content,
            'type_id' => $request->type_id,
            'status_id' => 42
        ]);

        return [
            'data' => $data,
            'message' => 'Overtime targets updated',
            'info' => "The targets has been successfully updated."
        ];
    }
}
