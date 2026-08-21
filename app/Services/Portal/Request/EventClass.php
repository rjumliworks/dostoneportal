<?php

namespace App\Services\Portal\Request;

use App\Models\RequestEvent;

class EventClass
{
    public function store($request)
    {
        $data = RequestEvent::create([
            'title' => $request->title,
            'type_id' => $request->type_id,
            'audience_id' => $request->audience_id,
            'mode_id' => $request->mode_id,
            'is_host' => $request->is_host ?? 0,
            'user_id' => \Auth::user()->id,
            'status_id' => 27
        ]);

        $data->load('type', 'audience', 'mode');

        return [
            'data' => [
                'value' => $data->id,
                'name' => $data->title,
                'type' => $data->type,
                'audience' => $data->audience,
                'mode' => $data->mode,
                'is_host' => $data->is_host,
                'location' => null,
            ],
            'message' => 'Event Tagged',
            'info' => 'Your event has been created and is now ready to be tagged on your request.'
        ];
    }
}
