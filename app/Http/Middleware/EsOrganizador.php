<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Organizadores;

class EsOrganizador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Buscar el organizador y obtener el user_id
        $organizador = Organizadores::where('user_id', Auth()->id())
            ->first();

        if($organizador){
            return $next($request);
        }else{
            return redirect()->back()->with('error', 'No eres organizador, debes pagar una suscripción');
        }

    }
}
