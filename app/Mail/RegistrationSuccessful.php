<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationSuccessful extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $session;
    public $wasFull;

    public function __construct($name, $session = null, $wasFull = false)
    {
        $this->name = $name;
        $this->session = $session;
        $this->wasFull = $wasFull;
    }

    public function build()
    {
        return $this->subject($this->wasFull ? 'DOST-IX Registration Successful — Session Full' : 'DOST-IX Registration Successful')
            ->view('emails.registration.success')
            ->with([
                'name' => $this->name,
                'session' => $this->session,
                'wasFull' => $this->wasFull,
            ]);
    }
}
