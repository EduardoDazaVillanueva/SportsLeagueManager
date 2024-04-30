<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Deportes;
use App\Models\User;
use App\Models\Jugadores;
use App\Models\ParticipaEnLiga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
{
    public function getWelcome()
    {
        return view('welcome', [
            'deportes' => Deportes::all(),
            'user' => Auth::user(),
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

    public function getPerfil(Request $request, User $user)
    {


        // Unir 'users' con 'jugadores' para obtener 'jugador_id'
        $jugador = Jugadores::where('user_id', $user->id)->first();

        if ($jugador) {
            // Unir con 'participa_en_ligas' para obtener las ligas donde el jugador participa
            $ligas = ParticipaEnLiga::where('jugadores_id', $jugador->id)
                ->join('ligas', 'participa_en_ligas.liga_id', '=', 'ligas.id')
                ->select(
                    'ligas.*'
                )
                ->get();

            return view('user.perfil', [
                'deportes' => Deportes::all(),
                'user' => $user,
                'ligas' => $ligas,
            ]);
        }else{
            return view('user.perfil', [
                'deportes' => Deportes::all(),
                'user' => $user,
                'ligas' => null
            ]);
        }
    }
}
