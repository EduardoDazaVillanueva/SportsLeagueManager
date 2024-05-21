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

        $liga = $request->route('liga');

        $organizadores_id = $liga->organizadores_id;

        // Buscar el organizador y obtener el user_id
        $organizador = Organizadores::where('organizadores.id', $organizadores_id)
            ->join('users', 'organizadores.user_id', '=', 'users.id')
            ->select('users.id')
            ->first();

        if($organizador->id == Auth()->id()){
            return $next($request);
        }else{
            return redirect()->back()->with('error', 'No eres el organizador de esta liga');
        }

    }
}
