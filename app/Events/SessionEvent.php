<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionEvent implements ShouldBroadcast 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data,$type,$code;

    public function __construct($data,$type,$code = null)
    {
        $this->data = $data;
        $this->type = $type;
        $this->code = $code;
    }

    public function broadcastOn(): array
    {
        return [
           new Channel('session'), 
        ];
    }

    public function broadcastWith()
    {
        return [
            'data' => $this->data,
            'type' => $this->type,
            'code' => $this->code
        ];
    }
}
