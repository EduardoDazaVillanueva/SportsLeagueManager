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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if ($jugador || $organizador) {
            if ($jugador) {
                $ligas = ParticipaEnLiga::where('jugadores_id', $jugador->id)
                    ->join('ligas', 'participa_en_ligas.liga_id', '=', 'ligas.id')
                    ->select('ligas.*')
                    ->paginate(5);

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
                $ligasPropias = Ligas::where('organizadores_id', $organizador->id)->paginate(5);

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
                'jugadores' => $jugadoresPorLigaAgrupados
            ]);
        } else {
            return view('user.perfil', [
                'deportes' => Deportes::all(),
                'user' => $user,
                'ligas' => null,
                'ligasPropias' => null,
                'jugadoresPorLigaAgrupados' => null
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
}
