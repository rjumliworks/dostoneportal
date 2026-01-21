<?php

namespace App\Services\HumanResource\Dtr;

use App\Models\Dtr;
use App\Http\Resources\HumanResource\Dtr\IndexResource;

class ViewClass
{   
    public function counts(){
        return [Dtr::where('is_completed',1)->count(),Dtr::where('is_completed',0)->count()];
    }

    public function list($request){
        $data = IndexResource::collection(
            Dtr::with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id')
            ->when($request->keyword, function ($query, $keyword) {
                $query->whereHas('user',function ($query) use ($keyword) {
                    $query->whereHas('profile',function ($query) use ($keyword) {
                        $query->when($keyword, function ($query, $keyword) {
                            $query->whereRaw('lastname LIKE ?', ['%' . $keyword . '%']);
                        });
                    });
                });
            })
            ->when($request->date, function ($query, $date) {
                $query ->where('date',$date);
            })
            ->when($request->status, function ($query, $status) {
                $query ->where('is_completed',$status);
            })
            ->orderBy('date', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }
}
