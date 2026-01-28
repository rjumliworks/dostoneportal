<?php

namespace App\Services\HumanResource\Visitor;

use App\Models\Visitor;
use App\Models\ListName;
use App\Http\Resources\HumanResource\Visitor\IndexResource;

class SaveClass
{
    public function store($request){
        $data = Visitor::create($request->all());
        $data = Visitor::with('type','status','affiliation','designation')->where('id',$data->id)->first();
        return [
            'data' => $data,
            'message' => 'Visitor creation was successful!', 
            'info' => "You've successfully added a new visitor."
        ];
    }

    public function name($request){
        $name = ListName::create($request->all());
        $data = ListName::findOrFail($name->id);
        $data = [
            'value' => $data->id,
            'name' => $data->name,
            'type' => $data->type
        ];
        return $data;
    }

    public function status($request){
        $data = Visitor::with('status','type','affiliation','designation')
        ->where('id',$request->id)->first();
        $data->status_id = $request->status_id;
        $data->save();

        return [
            'data' => new IndexResource($data->refresh()),
            'message' => 'Visitor update was successful!', 
            'info' => "You've successfully updated the selected visitor."
        ];
    }
}
