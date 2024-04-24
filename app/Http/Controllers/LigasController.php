<?php

namespace App\Http\Controllers;

use App\Models\Ligas;
use App\Models\Organizadores;
use App\Http\Controllers\Controller;
use App\Models\Deportes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

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
    public function create(string $deporteID)
    {
        return view('liga.create', ['deportes' => Deportes::all(), 'deporteID' => $deporteID, 'userID' => Auth::id()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        // Validar la entrada del usuario
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_final' => ['required', 'date'],
            'fecha_fin_inscripcion' => ['required', 'date'],
            'localidad' => ['required', 'string', 'max:255'],
            'sede' => ['required', 'string', 'max:255'],
            'dia_jornada' => ['required', 'integer', 'between:1,9'],
            'pnts_ganar' => ['required', 'integer', 'min:0'],
            'pnts_perder' => ['required', 'integer', 'min:0'],
            'pnts_empate' => ['required', 'integer', 'min:0'],
            'pnts_juego' => ['required', 'integer', 'min:0'],
            'txt_responsabilidad' => ['required', 'string', 'max:1000'],
            'deporte_id' => ['required',Rule::exists('deportes', 'id')],
            'logo' => ['nullable', 'image']
        ]);
        

        $userId = Auth::id(); // Obtenemos el ID del usuario actual

        // Verificar si el organizador ya existe
        $organizador = Organizadores::where('user_id', $userId)->first();
    
        if (!$organizador) {
            // Si no existe, crear un nuevo organizador
            $organizador = Organizadores::create(['user_id' => $userId]);
        }
    
        // Añadir el ID del organizador al conjunto de datos validados
        $validatedData['organizadores_id'] = $organizador->id;
    
        try {
            if ($request->hasFile('logo')) {
                $path = Storage::disk('public')->putFile('imagenes', $request->file('logo'));
                $validatedData['logo'] = basename($path);
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al almacenar el archivo: ' . $e->getMessage()]);
        }
        
        // Crear la nueva liga con los datos validados
        Ligas::create($validatedData);

        // Redireccionar con un mensaje de éxito
        return redirect()->route('welcome')->with('success', 'La liga ha sido creada con éxito.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Ligas $liga)
    {
        return view(
            'liga.liga',
            ['liga' => $liga]
        );
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
                'deportes' => Deportes::all(),
                'deporteID' => $deporte
            ]
        );
    }

    public function ligaClasificacion(Ligas $liga)
    {
        return view(
            'liga.ligaClasificacion',
            ['liga' => $liga]
        );
    }

    public function ligaJugadores(Ligas $liga)
    {
        return view(
            'liga.ligaJugadores',
            ['liga' => $liga]
        );
    }

    public function ligaPartidos(Ligas $liga)
    {
        return view(
            'liga.ligaPartidos',
            ['liga' => $liga]
        );
    }
}
