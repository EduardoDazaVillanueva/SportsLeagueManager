<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Ligas;
use App\Models\Organizadores;
use App\Models\ParticipaEnLiga;
use App\Models\Deportes;
use App\Models\Jugadores;
use App\Models\Jornadas;
use App\Models\JugadorJuegaJornada;
use App\Models\Partidos;
use App\Models\PartidoParticipaJugadores;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
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

    public function crearJornada(Ligas $liga)
    {
        $fechaInicio = Carbon::parse($liga->fecha_inicio);
        $fechaFinal = Carbon::parse($liga->fecha_final);

        $numSemanas = $fechaInicio->diffInWeeks($fechaFinal);

        for ($i = 0; $i < $numSemanas; $i++) {

            $fechaJornadaInicio = $fechaInicio->copy()->addWeeks($i);
            $fechaJornadaFin = $fechaJornadaInicio->copy()->addWeeks(1)->subDay();

            if ($fechaJornadaFin->greaterThan($fechaFinal)) {
                $fechaJornadaFin = $fechaFinal;
            }

            $dateDiff = $fechaJornadaInicio->diffInDays($fechaJornadaFin);

            if ($dateDiff == 6) {
                Jornadas::create([
                    'num_jornada' => $i + 1,
                    'fecha-inicio' => $fechaJornadaInicio->toDateTimeString(),
                    'fecha-final' => $fechaJornadaFin->toDateTimeString(),
                    'liga_id' => $liga->id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Jornadas creadas con éxito');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ($request->precio == "") {
            $request['precio'] = 0;
        }

        // Validar la entrada del usuario con una regla de validación personalizada
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
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
        $liga = Ligas::create($validatedData);

        $this->crearJornada($liga);

        return redirect("liga/deporte/{$validatedData['deporte_id']}")->with('success', 'La liga ha sido creada con éxito.');
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
            ->select('organizadores.id', 'users.name', 'users.telefono')
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


        $juegaJornada = false;

        if ($jugador) {
            $jornada = Jornadas::where('liga_id', $liga->id)->first();

            if ($jornada) {
                $juegaJornada = JugadorJuegaJornada::where('jornada_id', $jornada->id)
                    ->where('jugador_id', $jugador->id)
                    ->exists();
            } else {
                $juegaJornada = false;
            }
        } else {
            $juegaJornada = false;
        }

        $fechaJornada = $this->getFechaJornada($liga);

        // Devolver la vista con todos los datos
        return view('liga.liga', [
            'liga' => $liga,
            'user' => Auth::user(),
            'organizador' => $organizador,
            'esJugador' => $esJugador,
            'jugadores' => $jugadores,
            'juegaJornada' => $juegaJornada,
            'fechaJornada' => $fechaJornada,
            'mostrarDivRango' => $this->mostrarDivRango($fechaJornada),
            'mostrarBotonInscribirse' => $this->mostrarBotonInscribirse($liga->fecha_fin_inscripcion)
        ]);
    }

    private function getFechaJornada(Ligas $liga)
    {
        $fechaActual = Carbon::now();

        // Obtener la próxima jornada después de la fecha actual
        $jornada = Jornadas::where('liga_id', $liga->id)
            ->where('fecha-inicio', '>', $fechaActual)
            ->orderBy('fecha-inicio', 'asc')
            ->firstOrFail();

        $fecha = $jornada->getAttribute('fecha-inicio');

        return $fecha ? Carbon::parse($fecha)->format('Y-m-d') : null;
    }

    private function mostrarDivRango($fechaJornada)
    {
        $fechaJornada = Carbon::create($fechaJornada);
        $dateDiff = abs($fechaJornada->diffInDays(Carbon::now()));
        return $dateDiff < 5 && $dateDiff > 2;
    }

    private function mostrarBotonInscribirse($fechaFinInscripcion)
    {
        $fechaFinInscripcion = Carbon::create($fechaFinInscripcion);
        $dateDiff = $fechaFinInscripcion->diffInDays(Carbon::now());
        return $dateDiff < 0;
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
    public function ligaDeporte(Request $request, string $deporte)
    {
        // Obtener todas las localidades seleccionadas del request
        $localidadesSeleccionadas = $request->input('localidades', []);

        // Iniciar la consulta base
        $ligasQuery = Ligas::where('deporte_id', $deporte);

        // Filtrar por localidades seleccionadas, si hay alguna
        if (!empty($localidadesSeleccionadas)) {
            $ligasQuery->whereIn('localidad', $localidadesSeleccionadas);
        }

        // Obtener las ligas filtradas
        $ligas = $ligasQuery->get();

        // Obtener el nombre del deporte y todas las localidades
        $deporteNombre = Deportes::find($deporte);
        $localidades = Ligas::where('deporte_id', $deporte)->pluck('localidad')->unique()->values();

        // Para obtener detalles de jugadores por liga, usar `whereIn`
        $ligaIds = $ligas->pluck('id');

        // Obtener todos los jugadores de las ligas
        $jugadoresPorLiga = ParticipaEnLiga::whereIn('liga_id', $ligaIds)
            ->join('jugadores', 'participa_en_ligas.jugadores_id', '=', 'jugadores.id')
            ->join('users', 'jugadores.user_id', '=', 'users.id')
            ->select(
                'participa_en_ligas.liga_id',
                'jugadores.*',
                'users.name as user_name'
            )
            ->get();

        // Agrupar jugadores por liga_id para facilitar la consulta en la vista
        $jugadoresPorLigaAgrupados = $jugadoresPorLiga->groupBy('liga_id');

        return view(
            'liga.ligas',
            [
                'nombreDeporte' => $deporteNombre,
                'ligas' => $ligas,
                'deportes' => Deportes::all(),
                'deporteID' => $deporte,
                'user' => Auth::user(),
                'localidades' => $localidades,
                'jugadores' => $jugadoresPorLigaAgrupados
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

    public function ligaPartidos(Request $request, Ligas $liga)
    {
        $jornadas = Jornadas::where('liga_id', $liga->id)->get();

        // Obtener el número de jornada seleccionado del request
        $numJornada = $request->input('num_jornada', 1);

        // Obtener la jornada específica de la liga
        $jornada = Jornadas::where('liga_id', $liga->id)
            ->where('num_jornada', $numJornada)
            ->first();

        if (!$jornada) {
            return redirect()->back()->with('error', 'Jornada no encontrada');
        }

        $partidos = Partidos::where('jornada_id', $jornada->id)->get();

        $fechaInicio = Jornadas::where('liga_id', $liga->id)
            ->where('num_jornada', $numJornada)
            ->select('fecha-inicio')
            ->first();

        $fechaStringInicio = $fechaInicio ? Carbon::parse($fechaInicio->getAttribute('fecha-inicio'))->format('Y-m-d') : null;

        $fechaFinal = Jornadas::where('liga_id', $liga->id)
            ->where('num_jornada', $numJornada)
            ->select('fecha-final')
            ->first();

        $fechaStringFinal = $fechaFinal ? Carbon::parse($fechaFinal->getAttribute('fecha-final'))->format('Y-m-d') : null;


        $jugadores = collect();

        foreach ($partidos as $partido) {
            $jugadoresPartido = PartidoParticipaJugadores::where('partidos_id', $partido->id)
                ->join('jugadores', 'partido_participa_jugadores.jugador1_id', '=', 'jugadores.id')
                ->join('users', 'jugadores.user_id', '=', 'users.id')
                ->select('partido_participa_jugadores.*', 'users.name')
                ->get();

            $jugadores = $jugadores->concat($jugadoresPartido); // Concatenar resultados
        }

        return view('liga.ligaPartidos', [
            'liga' => $liga,
            'user' => Auth::user(),
            'jornadas' => $jornadas,
            'partidos' => $partidos,
            'numJornada' => $numJornada,
            'fechaInicio' => $fechaStringInicio,
            'fechaFinal' => $fechaStringFinal,
            'jugadores' => $jugadores,
        ]);
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

    public function jugarJornada(Request $request, string $ligaId, string $userId)
    {
        $fechaActual = Carbon::now();

        $jornada = Jornadas::where('liga_id', $ligaId)
            ->where('fecha-inicio', '>', $fechaActual)
            ->orderBy('fecha-inicio', 'asc')
            ->first();

        if (!$jornada) {
            return redirect()->back()->with('error', 'No hay jornadas próximas disponibles');
        }

        $jugador = Jugadores::where('user_id', $userId)->first();

        $validatedData = $request->validate([
            'dia_jornada' => ['required', 'array']
        ]);

        JugadorJuegaJornada::create([
            'jugador_id' => $jugador->id,
            'jornada_id' => $jornada->id,
            'dia_posible' => $validatedData['dia_jornada']
        ]);

        return redirect()->back()->with('success', 'Jugador inscrito a la próxima jornada con éxito');
    }
}
