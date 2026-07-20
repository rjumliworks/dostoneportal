<?php

namespace App\Services\Events\Exhibit;


use Hashids\Hashids;
use App\Models\EventExhibitor;
use App\Http\Resources\Event\Exhibitor\ViewResource;
class ViewClass
{
    public function view($id){
       
        $hashids = new Hashids('krad',10);
        $key = $hashids->decode($id);

        $data = new ViewResource(
            EventExhibitor::with([
                'event',
                'contact',
                'type',
                'event.detail.region:code,name,region',
                'event.detail.province:code,name',
                'event.detail.municipality:code,name',
                'event.detail.barangay:code,name',
                'visitors.participant',
                'feedbackable.participant',
            ])
            ->where('id', $key[0])
            ->first()
        );
        return $data;
    }
}
