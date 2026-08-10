<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Pure signaling relay for the Scanner.vue -> FaceRecognitionPage.jsx WebRTC
 * handoff (see VipController::signal()). Never carries video itself - just
 * SDP offers/answers and ICE candidates bounced between the two apps over
 * the same Reverb connection both already use for VipEvent.
 */
class VipSignalEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $signal;

    public function __construct($signal)
    {
        $this->signal = $signal;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('vip-signal'),
        ];
    }

    public function broadcastWith()
    {
        return [
            'signal' => $this->signal,
        ];
    }
}
