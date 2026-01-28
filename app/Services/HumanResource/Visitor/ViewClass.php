<?php

namespace App\Services\HumanResource\Visitor;

use Hashids\Hashids;
use App\Models\Visitor;
use App\Models\ListName;
use App\Http\Resources\HumanResource\Visitor\IndexResource;

class ViewClass
{
    public function counts($types){
        foreach($types as $type){
            $counts[] = Visitor::where('status_id', $type)->count();
        }
        return $counts;
    }

    public function visitor($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = new IndexResource(
           Visitor::query()
            ->with('type','status','affiliation','designation','logs','faces')
            ->where('id',$id)->first()
        );
        return $data;
    }

    public function lists($request){
        $data = IndexResource::collection(
            Visitor::with('type','status','affiliation','designation')
            ->when($request->keyword, function ($query,$keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->when($request->type, function ($query,$type) {
                $query->where('type_id',$type);
            })
            ->when($request->status, function ($query,$status) {
                $query->where('status_id',$status);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function search($request){
        $keyword = $request->keyword;
        $type = $request->type;

        $data = ListName::where('name', 'LIKE', "%{$keyword}%")
        ->where('type',$type)
        ->where('is_active',1)
        ->limit(20)
        ->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
            ];
        });
        return $data;
    }
}
