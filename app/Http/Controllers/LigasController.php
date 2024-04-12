<?php

namespace App\Http\Controllers;

use App\Models\Ligas;
use App\Http\Controllers\Controller;
use App\Models\Deportes;
use Illuminate\Http\Request;

class LigasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('liga.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $liga = $request->validate([
            'nombre' => ['required', 'string'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_final' => ['required', 'date'],
            'fecha_fin_inscripcion' => ['required', 'date'],
            'localidad' => ['required', 'string'],
            'sede' => ['required', 'string'],
            'dia_jornada' => ['required', 'integer'],
            'pnts_ganar' => ['required', 'integer'],
            'pnts_perder' => ['required', 'integer'],
            'pnts_empate' => ['required', 'integer'],
            'pnts_juego' => ['required', 'integer'],
            'txt_responsabilidad' => ['required', 'string'],
        ]);
        Ligas::create($liga);
        return redirect()->route('liga/1')
            ->withSuccess('La liga ha sido creada con exito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ligas $ligas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ligas $ligas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ligas $ligas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ligas $ligas)
    {
        //
    }

    //mostrar todas las ligas que tengan el idDeporte
    public function ligaDeporte(string $deporte)
    {
        $ligas = Ligas::where('deporte_id', $deporte)->get();
        $deporteNombre = Deportes::where('id', $deporte)->first();
        return view(
            'liga.ligas',
            [
                'nombreDeporte' => $deporteNombre,
                'ligas' => $ligas,
                'deportes' => Deportes::all()
            ]
        );
    }
}
