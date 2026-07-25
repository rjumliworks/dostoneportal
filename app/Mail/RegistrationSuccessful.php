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

    public function __construct($name, $session = null)
    {
        $this->name = $name;
        $this->session = $session;
    }

    public function build()
    {
        return $this->subject('DOST-IX Registration Successful')
            ->view('emails.registration.success')
            ->with([
                'name' => $this->name,
                'session' => $this->session,
            ]);
    }
}
