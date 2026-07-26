<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\RegistrationSuccessful;
use App\Models\EventSession;
use Illuminate\Support\Facades\Mail;

class RegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $name;
    protected $sessionId;
    protected $wasFull;

    public function __construct($email, $name, $sessionId = null, $wasFull = false)
    {
        $this->email = $email;
        $this->name = $name;
        $this->sessionId = $sessionId;
        $this->wasFull = $wasFull;
    }

    public function handle(): void
    {
        $session = $this->sessionId
            ? EventSession::with('venue', 'schedules')->find($this->sessionId)
            : null;

        Mail::to($this->email)->send(new RegistrationSuccessful($this->name, $session, $this->wasFull));
    }
}
