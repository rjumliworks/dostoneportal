<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CapacityEvent implements ShouldBroadcast 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $count, $session;

    public function __construct($count,$session)
    {
        $this->count = $count;
        $this->session = $session;
    }

    public function broadcastOn(): array
    {
        return [
           new Channel('capacity'), 
        ];
    }

    public function broadcastWith()
    {
        return [
            'count' => $this->count,
            'session' => $this->session
        ];
    }
}
