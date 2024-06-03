<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Deportes;
use App\Models\User;
use App\Models\Jugadores;
use App\Models\Ligas;
use App\Models\Organizadores;
use App\Models\ParticipaEnLiga;
use App\Models\Productos;
use App\Models\UsuarioCompraProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class ViewController extends Controller
{
    public function getWelcome()
    {
        return view('welcome', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
            'productos' => Productos::all()
        ]);
    }

    public function getCreate()
    {
        return view('liga.create', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
        ]);
    }

    public function getFAQ()
    {
        return view('faq', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
        ]);
    }

    public function getCookies()
    {
        return view('cookies', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
        ]);
    }

    public function getResponsabilidad()
    {
        return view('responsabilidad', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
        ]);
    }

    public function getSobreNosotros()
    {
        return view('sobreNosotros', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
        ]);
    }

    public function getPerfil(Request $request, User $user)
    {
        // Unir 'users' con 'jugadores' para obtener 'jugador_id'
        $jugador = Jugadores::where('user_id', $user->id)->first();
        $organizador = Organizadores::where('user_id', $user->id)->first();

        $ligas = collect();
        $ligasPropias = collect();
        $jugadoresPorLigaAgrupados = collect();

        $suscripcion = UsuarioCompraProducto::where('user_id', $user->id)
            ->whereIn('producto_id', [1, 2, 3, 4])
            ->orderBy('created_at', 'desc')
            ->first();

        $diasRestantes = null;

        if ($suscripcion) {
            if ($suscripcion->producto_id == 1) {
                $diasRestantes = $this->comprobarSuscripcion($suscripcion, 30);
            } elseif ($suscripcion->producto_id == 2) {
                $diasRestantes = $this->comprobarSuscripcion($suscripcion, 90);
            } elseif ($suscripcion->producto_id == 3) {
                $diasRestantes = $this->comprobarSuscripcion($suscripcion, 365);
            } else {
                $diasRestantes = -2;
            }
        }

        if ($jugador || $organizador) {
            if ($jugador) {
                $ligas = ParticipaEnLiga::where('jugadores_id', $jugador->id)
                    ->join('ligas', 'participa_en_ligas.liga_id', '=', 'ligas.id')
                    ->select('ligas.*')
                    ->paginate(4);

                $ligaIds = $ligas->pluck('id');

                // Obtener todos los jugadores de las ligas en las que participa
                $jugadoresPorLiga = ParticipaEnLiga::whereIn('liga_id', $ligaIds)
                    ->join('jugadores', 'participa_en_ligas.jugadores_id', '=', 'jugadores.id')
                    ->join('users', 'jugadores.user_id', '=', 'users.id')
                    ->select('participa_en_ligas.liga_id', 'jugadores.*', 'users.name as user_name')
                    ->get();

                // Agrupar jugadores por liga_id para facilitar la consulta en la vista
                $jugadoresPorLigaAgrupados = $jugadoresPorLigaAgrupados->merge($jugadoresPorLiga->groupBy('liga_id'));
            }

            if ($organizador) {
                $ligasPropias = Ligas::where('organizadores_id', $organizador->id)->paginate(4);

                $ligaIds = $ligasPropias->pluck('id');

                // Obtener todos los jugadores de las ligas que organiza
                $jugadoresPorLiga = ParticipaEnLiga::whereIn('liga_id', $ligaIds)
                    ->join('jugadores', 'participa_en_ligas.jugadores_id', '=', 'jugadores.id')
                    ->join('users', 'jugadores.user_id', '=', 'users.id')
                    ->select('participa_en_ligas.liga_id', 'jugadores.*', 'users.name as user_name')
                    ->get();

                // Agrupar jugadores por liga_id para facilitar la consulta en la vista
                $jugadoresPorLigaAgrupados = $jugadoresPorLigaAgrupados->merge($jugadoresPorLiga->groupBy('liga_id'));
            }

            return view('user.perfil', [
                'deportes' => Deportes::all(),
                'user' => $user,
                'ligas' => $ligas,
                'ligasPropias' => $ligasPropias,
                'jugadores' => $jugadoresPorLigaAgrupados,
                'suscripcion' => $diasRestantes
            ]);
        } else {
            return view('user.perfil', [
                'deportes' => Deportes::all(),
                'user' => $user,
                'ligas' => null,
                'ligasPropias' => null,
                'jugadoresPorLigaAgrupados' => null,
                'suscripcion' => $diasRestantes
            ]);
        }
    }


    public function get404()
    {
        return view('errors.404', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
        ]);
    }

    private function comprobarSuscripcion($suscripcion, $dias)
    {
        $fechaCreacion = Carbon::parse($suscripcion->created_at);
        $fechaExpiracion = $fechaCreacion->addDays($dias);
        $fechaActual = Carbon::now();

        if ($fechaActual->greaterThanOrEqualTo($fechaExpiracion)) {
            // La suscripción ha expirado
            return -1;
        } else {
            // La suscripción aún es válida, devolver cuántos días quedan
            return floor($fechaActual->diffInDays($fechaExpiracion));
        }
    }

    public function getAjustes(){
        return view('sorpresa.ajustes', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
        ]);
    }
}
