<?php

namespace App\Http\Middleware;

use App\Models\Jornadas;
use App\Models\Jugadores;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PartidoParticipaJugadores;
use App\Models\Partidos;

class ParticipaPartido
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jugadoresDeEstePartido = PartidoParticipaJugadores::where('partidos_id', $request->route('idPartido'))
            ->join('jugadores', 'partido_participa_jugadores.jugadores_id', '=', 'jugadores.id')
            ->select('jugadores.id')
            ->get();

        $partido = Partidos::where('id', $request->route('idPartido'))->first();
        $jornada = Jornadas::where('id', $partido->jornada_id)->first();

        foreach ($jugadoresDeEstePartido as $jugador) {

            $user_id = Jugadores::where('id', $jugador->id)
                ->select('user_id')
                ->first();

            if ($user_id->user_id == Auth()->id() && $jornada->{'fecha-final'} <= now()) {
                return $next($request);
            }
        }

        return redirect()->back()->with('error', 'No puedes añadir resultado a este partido');
    }
}
