<?php

namespace App\Http\Controllers;

use App\Models\Ligas;
use App\Models\User;
use App\Mail\InvitarLiga;
use App\Mail\ResultadoPartido;
use App\Mail\CrearPartido;
use App\Mail\VerificarElEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function enviarCorreoInvitacion(string $email, Ligas $liga)
    {
        // Envía el correo electrónico al usuario especificado
        Mail::to($email)->send(new InvitarLiga(Auth()->id(), $liga));

        return redirect()->back()->with('success', 'Correo electrónico de invitación enviado correctamente.');
    }

    public function enviarCorreoResultado(User $user, Ligas $liga)
    {
        // Envía el correo electrónico al usuario especificado
        Mail::to($user->email)->send(new ResultadoPartido($user, $liga));
    }

    public function enviarCorreoPartido(User $user, Ligas $liga)
    {
        // Envía el correo electrónico al usuario especificado
        Mail::to($user->email)->send(new CrearPartido($user, $liga));
    }

    public function enviarCorreoVerificacion(User $user)
    {
        // Envía el correo electrónico al usuario especificado
        Mail::to($user->email)->send(new VerificarElEmail($user));
    }
}
