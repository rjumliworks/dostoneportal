<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; 
    protected $pdf1;
    protected $pdf2;

    public function __construct($data, $pdf1, $pdf2)
    {
        $this->data = $data;
        $this->pdf1  = $pdf1;
        $this->pdf2 = $pdf2;
    }

    public function build()
    {
        return $this->subject('Your Certificates')
            ->view('emails.certificate') // simple blade for the email body
            ->attachData(
                base64_decode($this->pdf1),
                'Appearance.pdf',
                ['mime' => 'application/pdf']
            )
            ->attachData(
                base64_decode($this->pdf2),
                'Participation.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
