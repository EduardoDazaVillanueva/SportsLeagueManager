<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CrearPartido extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $liga;

    public function __construct($user, $liga)
    {
        $this->user = $user;
        $this->liga = $liga;
    }

    public function build()
    {
        return $this->view('emails.recordatorioPartido')->with('liga', $this->liga);;
    }
}
