<?php

namespace App\Mail;

use App\Models\Ligas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResultadoPartido extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $liga;

    public function __construct($user, Ligas $liga)
    {
        $this->user = $user;
        $this->liga = $liga;
    }

    public function build()
    {
        return $this->view('emails.resultadoPartido')
            ->with('liga', $this->liga);
    }
}
