<?php

namespace App\Http\Controllers;

use App\Models\Ligas;
use App\Models\Organizadores;
use App\Models\ParticipaEnLiga;
use App\Http\Controllers\Controller;
use App\Models\Deportes;
use App\Models\Jugadores;
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
        return view('liga.create', ['deportes' => Deportes::all(), 'deporteID' => $deporteID, 'user' => Auth::user()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ($request->txt_inscripcion == "") {
            $request['precio'] = 0;
        }

        dd($request);

        // Validar la entrada del usuario con una regla de validación personalizada
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'fecha_fin_inscripcion' => ['required', 'date'],
            'fecha_final' => ['required', 'date'],
            'localidad' => ['required', 'string', 'max:255'],
            'sede' => ['required', 'string', 'max:255'],
            'dia_jornada' => ['required', 'array'],
            'pnts_ganar' => ['required', 'integer', 'min:0'],
            'pnts_perder' => ['required', 'integer', 'min:0'],
            'pnts_empate' => ['nullable', 'integer', 'min:0'],
            'pnts_juego' => ['nullable', 'integer', 'min:0'],
            'txt_responsabilidad' => ['required', 'string', 'max:1000'],
            'deporte_id' => ['required', Rule::exists('deportes', 'id')],
            'logo' => ['nullable', 'file', 'mimes:jpg,png,gif,jpeg'],
            'precio' => ['required', 'integer'],
            'posicion' => [],

            // Validación personalizada para fechas
            'fecha_fin_inscripcion' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    // Validar que fecha_fin_inscripcion sea menor que fecha_inicio
                    if ($value >= $request->fecha_inicio) {
                        $fail("La fecha de fin de inscripción debe ser anterior a la fecha de inicio de la liga.");
                    }
                }
            ],
            'fecha_inicio' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    // Validar que fecha_inicio sea menor que fecha_final
                    if ($value >= $request->fecha_final) {
                        $fail("La fecha de inicio de la liga debe ser anterior a la fecha de fin de la liga.");
                    }
                }
            ],
        ]);

        $deporteID = $validatedData["deporte_id"];
        $userId = Auth::id(); // Obtener el ID del usuario actual

        // Verificar si el organizador ya existe y crear si no
        $organizador = Organizadores::where('user_id', $userId)->first() ?? Organizadores::create(['user_id' => $userId]);
        $validatedData['organizadores_id'] = $organizador->id;

        // Manejo de carga de archivos
        if ($request->hasFile('logo')) {
            try {
                $path = Storage::disk('public')->putFile('imagenes', $request->file('logo'));
                $validatedData['logo'] = basename($path);
            } catch (Exception $e) {
                return redirect()->back()->withErrors(['error' => 'Error al almacenar el archivo: ' . $e->getMessage()]);
            }
        }

        // Crear la nueva liga
        Ligas::create($validatedData);

        // Redireccionar con mensaje de éxito
        return redirect("liga/deporte/$deporteID")->with('success', 'La liga ha sido creada con éxito.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Ligas $liga)
    {
        // Obtener el organizadores_id a partir de la liga
        $organizadores_id = $liga->organizadores_id;

        // Buscar el organizador y obtener el user_id
        $organizador = Organizadores::where('organizadores.id', $organizadores_id)
            ->join('users', 'organizadores.user_id', '=', 'users.id')
            ->select('organizadores.id', 'users.*')
            ->first();


        // Obtener el ID del usuario autenticado
        $userId = Auth::id();

        // Verificar si el usuario es un jugador
        $jugador = Jugadores::where('user_id', $userId)->first();

        // Por defecto, esJugador es falso
        $esJugador = false;

        if ($jugador) {
            // Verificar si el jugador está en la liga
            $esJugador = ParticipaEnLiga::where('liga_id', $liga->id)
                ->where('jugadores_id', $jugador->id)
                ->exists();
        }

        $jugadores = ParticipaEnLiga::where('liga_id', $liga->id)
            ->join('jugadores', 'participa_en_ligas.jugadores_id', '=', 'jugadores.id')
            ->join('users', 'jugadores.user_id', '=', 'users.id')
            ->select(
                'participa_en_ligas.*',
                'users.name as user_name'
            )
            ->get();

        // Devolver la vista con todos los datos
        return view(
            'liga.liga',
            [
                'liga' => $liga,
                'user' => Auth::user(),
                'organizador' => $organizador,
                'esJugador' => $esJugador,
                'jugadores' => $jugadores,
            ]
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
        $localidades = $ligas->pluck('localidad')->unique();

        return view(
            'liga.ligas',
            [
                'nombreDeporte' => $deporteNombre,
                'ligas' => $ligas,
                'deportes' => Deportes::all(),
                'deporteID' => $deporte,
                'user' => Auth::user(),
                'localidades' => $localidades,
            ]
        );
    }

    public function ligaClasificacion(Ligas $liga)
    {
        // Obtener todos los jugadores que participan en la liga
        $jugadores = ParticipaEnLiga::where('liga_id', $liga->id)
            ->join('jugadores', 'participa_en_ligas.jugadores_id', '=', 'jugadores.id')
            ->join('users', 'jugadores.user_id', '=', 'users.id')
            ->select(
                'participa_en_ligas.*',
                'users.name as user_name',
                'users.id as user_id'
            )
            ->orderBy('participa_en_ligas.puntos', 'desc')
            ->get();

        return view(
            'liga.ligaClasificacion',
            [
                'liga' => $liga,
                'user' => Auth::user(),
                'jugadores' => $jugadores,
            ]
        );
    }

    public function ligaJugadores(Ligas $liga)
    {
        $jugadores = ParticipaEnLiga::where('liga_id', $liga->id)
            ->join('jugadores', 'participa_en_ligas.jugadores_id', '=', 'jugadores.id')
            ->join('users', 'jugadores.user_id', '=', 'users.id')
            ->select(
                'participa_en_ligas.*',
                'users.name as user_name',
                'users.id as user_id'
            )
            ->get();

        return view(
            'liga.ligaJugadores',
            [
                'liga' => $liga,
                'user' => Auth::user(),
                'jugadores' => $jugadores
            ]
        );
    }

    public function ligaPartidos(Ligas $liga)
    {
        return view(
            'liga.ligaPartidos',
            [
                'liga' => $liga,
                'user' => Auth::user()
            ]
        );
    }

    public function inscribirse(string $ligaId, string $userId)
    {
        try {
            // Verificar si el jugador ya existe
            $jugador = Jugadores::where('user_id', $userId)->first();

            if (!$jugador) {
                // Si no existe, crea un nuevo jugador
                $jugador = Jugadores::create([
                    'user_id' => $userId
                ]);
            }

            // Añadir al jugador en la tabla participa_en_liga
            ParticipaEnLiga::create([
                'liga_id' => $ligaId,
                'jugadores_id' => $jugador->id,
            ]);



            return redirect()->back()->with('success', 'Jugador inscrito en la liga con éxito.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al inscribir al jugador: ' . $e->getMessage()]);
        }
    }
}
