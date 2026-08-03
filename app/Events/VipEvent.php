<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VipEvent implements ShouldBroadcast 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $vip;

    public function __construct($vip)
    {
        $this->vip = $vip;
    }

    public function broadcastOn(): array
    {
        return [
           new Channel('vip'), 
        ];
    }

    public function broadcastWith()
    {
        return [
            'vip' => $this->vip
        ];
    }
}
