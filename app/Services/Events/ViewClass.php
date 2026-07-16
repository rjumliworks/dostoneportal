<?php

namespace App\Services\Events;

use Hashids\Hashids;
use App\Models\Event;
use App\Http\Resources\Event\ViewResource;
use App\Http\Resources\Event\IndexResource;

class ViewClass
{
     public function lists($request){
        $data = IndexResource::collection(
            Event::with('venues')
            ->with('exhibitors.contact')
            ->with('sessions.venue','sessions.detail','sessions.schedules','sessions.status','sessions.participants','sessions.attendees','sessions.managers.user.profile')
            ->with('detail.region:code,name,region','detail.province:code,name','detail.municipality:code,name','detail.barangay:code,name')
            ->when($request->keyword, function ($query,$keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function view($id){
        $hashids = new Hashids('krad',10);
        $key = $hashids->decode($id);
  
        $data = new ViewResource(
            Event::with('venues')
             ->with('exhibitors.contact')
            ->with('sessions.venue','sessions.detail','sessions.schedules','sessions.managers.user.profile')
            ->with('detail.region:code,name,region','detail.province:code,name','detail.municipality:code,name','detail.barangay:code,name')
            ->where('id',$key[0])->first()
        );
   
        return $data;
    }
}
