<?php

namespace App\Http\Controllers;

use App\Models\Ligas;
use App\Models\Organizadores;
use App\Models\ParticipaEnLiga;
use App\Models\Deportes;
use App\Models\Jugadores;
use App\Models\Jornadas;
use App\Models\JugadorJuegaJornada;
use App\Models\Partidos;
use App\Models\PartidoParticipaJugadores;
use App\Models\UsuarioInvitaUsuario;
use App\Models\User;
use App\Models\JugadorTieneEquipo;

use Illuminate\Support\Facades\DB;
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
     * Enviar a la vista del formulario para crear una liga
     */
    public function create(string $deporteID)
    {
        return view('liga.create', ['deportes' => Deportes::all(), 'deporteID' => $deporteID, 'user' => Auth::user()]);
    }

    /**
     * Crea una jornada teniendo en cuenta la fecha de inicio y la de fin de la liga
     */
    public function crearJornada(Ligas $liga)
    {
        $fechaInicio = Carbon::parse($liga->fecha_inicio);
        $fechaFinal = Carbon::parse($liga->fecha_final);

        //Cuenta el numero de semanas entre el inicio y el fin
        $numSemanas = $fechaInicio->diffInWeeks($fechaFinal);

        //Recorre el bucle por cada semana
        for ($i = 0; $i < $numSemanas; $i++) {

            $fechaJornadaInicio = $fechaInicio->copy()->addWeeks($i);
            $fechaJornadaFin = $fechaJornadaInicio->copy()->addWeeks(1)->subDay();

            //Si la fecha de fin de la jornada se pasa de la fecha de fin de la liga
            //se pone como máximo la fecha de fin liga
            if ($fechaJornadaFin->greaterThan($fechaFinal)) {
                $fechaJornadaFin = $fechaFinal;
            }

            $dateDiff = $fechaJornadaInicio->diffInDays($fechaJornadaFin);

            //Si la diferencia es igual a 6 (una semana), se crea la jornada
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
     * Función que se llama desde la vista liga.create.
     * Sirve para comprobar los campos y almacenar la liga para crearla
     */
    public function store(Request $request)
    {
        //Si no hay ningún valor en el campo "precio", significa que es gratis
        if (empty($request->precio)) {
            $request['precio'] = 0;
        }

        // Si no hay ningún valor en el campo "pnts_juego", significa que es 0
        if (empty($request->pnts_juego)) {
            $request['pnts_juego'] = 0;
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
            'pnts_empate' => ['required', 'integer', 'min:0'],
            'pnts_juego' => ['required', 'integer', 'min:0'],
            'txt_responsabilidad' => ['required', 'string', 'max:2000'],
            'deporte_id' => ['required', Rule::exists('deportes', 'id')],
            'logo' => ['nullable', 'file', 'mimes:jpg,png,gif,jpeg', 'max:2048'],
            'precio' => ['required', 'integer', 'min:0'],
            'numPistas' => ['required', 'integer', 'min:1'],
            'primera_hora' => ['required', 'date_format:H:i'],
            'ultima_hora' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value <= $request->primera_hora) {
                        $fail("La última hora debe ser posterior a la primera hora.");
                    }
                }
            ],
            'fecha_fin_inscripcion' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if (isset($request->fecha_inicio) && $value >= $request->fecha_inicio) {
                        $fail("La fecha de fin de inscripción debe ser anterior a la fecha de inicio de la liga.");
                    }
                }
            ],
            'fecha_inicio' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value >= $request->fecha_final) {
                        $fail("La fecha de inicio de la liga debe ser anterior a la fecha de fin de la liga.");
                    }
                }
            ],
        ]);

        //Obtener el id del deporte y del usuario actual
        $deporteID = $validatedData["deporte_id"];
        $userId = Auth::id();

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

        //Crear las jornadas
        $this->crearJornada($liga);

        return redirect("liga/deporte/{$deporteID}")->with('success', 'La liga ha sido creada con éxito.');
    }


    /**
     * Mostrar la vista de una liga en específico
     */
    public function show(Ligas $liga)
    {
        // Obtener el organizadores_id a partir de la liga
        $organizadores_id = $liga->organizadores_id;

        // Buscar el organizador y obtener el user_id
        $organizador = Organizadores::where('organizadores.id', $organizadores_id)
            ->join('users', 'organizadores.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.telefono')
            ->first();

        //Obtener la fecha de la siguiente jornada
        $fechaJornada = $this->getFechaJornada($liga);

        // Obtener el ID del usuario autenticado
        $userId = Auth::id();

        // Verificar si el usuario es un jugador
        $jugador = Jugadores::where('user_id', $userId)->first();

        $esJugador = false;

        if ($jugador) {
            // Verificar si el jugador está en la liga
            $esJugador = ParticipaEnLiga::where('liga_id', $liga->id)
                ->where('jugadores_id', $jugador->id)
                ->exists();
        }

        //Seleccionar todos los jugadores, para despues hacer un count()
        $jugadores = ParticipaEnLiga::where('liga_id', $liga->id)
            ->join('jugadores', 'participa_en_ligas.jugadores_id', '=', 'jugadores.id')
            ->join('users', 'jugadores.user_id', '=', 'users.id')
            ->select(
                'participa_en_ligas.*',
                'users.name as user_name'
            )
            ->get();


        $juegaJornada = false;

        $jornada = Jornadas::where('liga_id', $liga->id)
            ->where('fecha-inicio', $fechaJornada)
            ->first();

        if ($jugador) {
            //Si existe la jornada y el jugador la juega, se guarda en la variable
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

        $fecha2Dias = $this->comprobarFecha($fechaJornada);

        $hayPartidos = Partidos::where('jornada_id', $jornada->id)
            ->exists();

        if ($fecha2Dias && !$hayPartidos) {
            switch ($liga->deporte_id) {
                case '1':
                    $this->crearPartidosPorDia($liga->id, 2);
                    break;

                case '2':
                    $this->crearPartidosPorDia($liga->id, 10);
                    break;

                case '3':
                    $this->crearPartidosPorDia($liga->id, 2);
                    break;

                case '4':
                    $this->crearPartidosPorDia($liga->id, 4);
                    break;
            }
        }

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
            'mostrarBotonInscribirse' => $this->mostrarBotonInscribirse($liga->fecha_fin_inscripcion),
        ]);
    }

    /**
     * Obtener la fecha de la próxima jornada
     */
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

    /**
     * Comprobar si tiene que mostrar la alerta.
     *
     * Si la fecha de la jornada es en menos de 5 días y más de 2
     */
    private function mostrarDivRango($fechaJornada)
    {
        $fechaJornada = Carbon::create($fechaJornada);
        $dateDiff = abs($fechaJornada->diffInDays(Carbon::now()));
        return $dateDiff < 5 && $dateDiff > 2;
    }

    /**
     * Comprobar si la fecha de inscripción no ha pasado
     */
    private function mostrarBotonInscribirse($fechaFinInscripcion)
    {
        $fechaFinInscripcion = Carbon::create($fechaFinInscripcion);
        $dateDiff = $fechaFinInscripcion->diffInDays(Carbon::now());
        return $dateDiff < 0;
    }

    /**
     * Enviar a la vista de editar la liga
     */
    public function edit(Ligas $liga)
    {

        return view('liga.edit', [
            'liga' => $liga,
            'deportes' => Deportes::all(),
            'user' => Auth::user()
        ]);
    }

    /**
     * Comprobar los valores y actualizar la liga
     */
    public function update(Request $request, Ligas $liga)
    {
        // Validar solo los campos que están presentes en la solicitud
        $validatedData = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'numPistas' => ['sometimes', 'required', 'integer', 'min:0'],
            'pnts_ganar' => ['sometimes', 'required', 'integer', 'min:0'],
            'pnts_perder' => ['sometimes', 'required', 'integer', 'min:0'],
            'pnts_empate' => ['sometimes', 'required', 'integer', 'min:0'],
            'pnts_juego' => ['sometimes', 'required', 'integer', 'min:0'],
            'txt_responsabilidad' => ['sometimes', 'required', 'string', 'max:1000'],
            'logo' => ['sometimes', 'required', 'file', 'mimes:jpg,png,gif,jpeg'],
        ]);

        if ($request->hasFile('logo')) {
            try {
                $path = Storage::disk('public')->putFile('imagenes', $request->file('logo'));
                // Incluir el nombre del archivo en validatedData
                $validatedData['logo'] = basename($path);
            } catch (Exception $e) {
                return redirect()->back()->withErrors(['error' => 'Error al almacenar el archivo: ' . $e->getMessage()]);
            }
        }

        // Remover campos nulos o vacíos del arreglo validado
        $cleanedData = array_filter($validatedData, function ($value) {
            return !is_null($value) && $value !== '';
        });

        // Actualizar solo los campos que han sido validados y enviados
        $liga->update($cleanedData);

        return redirect()->route('liga.show', ['liga' => $liga->id])->with('success', 'La liga ha sido actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ligas $ligas)
    {
        //
    }

    /**
     * Mostrar todas las ligas de un deporte
     */
    public function ligaDeporte(Request $request, string $deporte)
    {
        // Iniciar la consulta base
        $ligasQuery = Ligas::where('deporte_id', $deporte);

        // Obtener todas las localidades seleccionadas del request
        $localidadesSeleccionadas = $request->input('localidades', []);
        $fechaInicioSeleccionada = $request->input('fechaInicio');
        $fechaFinalSeleccionada = $request->input('fechaFinal');
        $rangoJugadoresSeleccionada = $request->input('rangoJugadores', []);


        // Filtrar por localidades seleccionadas, si hay alguna
        if (!empty($localidadesSeleccionadas)) {
            $ligasQuery->whereIn('localidad', $localidadesSeleccionadas);
        }

        // Filtrar por fecha de inicio, si se proporciona
        if (!empty($fechaInicioSeleccionada)) {
            $ligasQuery->where('fecha_inicio', '>=', $fechaInicioSeleccionada);
        }

        // Filtrar por fecha final, si se proporciona
        if (!empty($fechaFinalSeleccionada)) {
            $ligasQuery->where('fecha_final', '<=', $fechaFinalSeleccionada);
        }

        // Filtrar por rango de jugadores, si se proporciona
        if (!empty($rangoJugadoresSeleccionada)) {
            $ligasQuery->whereHas('participaEnLigas', function ($query) use ($rangoJugadoresSeleccionada) {
                if (strpos($rangoJugadoresSeleccionada, '-') !== false) {
                    $maxJugadores = (int)str_replace('-', '', $rangoJugadoresSeleccionada);
                    $query->havingRaw('COUNT(jugadores_id) <= ?', [$maxJugadores]);
                } elseif (strpos($rangoJugadoresSeleccionada, '+') !== false) {
                    $minJugadores = (int)str_replace('+', '', $rangoJugadoresSeleccionada);
                    $query->havingRaw('COUNT(jugadores_id) >= ?', [$minJugadores]);
                }
            });
        }


        // Obtener las ligas filtradas
        $ligas = $ligasQuery->paginate(7);


        // Obtener el nombre del deporte y todas las localidades
        $deporteNombre = Deportes::find($deporte);
        $localidades = Ligas::where('deporte_id', $deporte)->pluck('localidad')->unique()->values();

        $ligaIds = $ligas->pluck('id');

        // Para obtener detalles de jugadores por liga, usar `whereIn`
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

    /**
     * Enviar a la vista de clasificación de una liga específica
     */
    public function ligaClasificacion(Ligas $liga)
    {
        //Obtener todos los jugadores que participan en la liga, ordenados por puntos
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

    /**
     * Enviar a la vista de jugadores de una liga específica
     */
    public function ligaJugadores(Ligas $liga)
    {
        //Obtener todos los jugadores que participan en la liga
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

    /**
     * Enviar a la vista de los partidos de una liga
     */
    public function ligaPartidos(Request $request, Ligas $liga)
    {
        //Seleccionar todas las jornadas de la liga
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

        switch ($liga->deporte_id) {
            case '1':
                $jugadoresPorPartido = 2;
                break;

            case '2':
                $jugadoresPorPartido = 10;
                break;

            case '3':
                $jugadoresPorPartido = 2;
                break;

            case '4':
                $jugadoresPorPartido = 4;
                break;
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

        $jugadoresPartido = collect();

        foreach ($partidos as $partido) {

            $jugadoresDeEstePartido = PartidoParticipaJugadores::where('partidos_id', $partido->id)
                ->join('jugadores', 'partido_participa_jugadores.jugadores_id', '=', 'jugadores.id')
                ->join('users', 'jugadores.user_id', '=', 'users.id')
                ->select('users.name', 'users.id', DB::raw($partido->id . ' as partido_id'))
                ->get();

            // Concatenar resultados a la colección
            $jugadoresPartido = $jugadoresPartido->concat($jugadoresDeEstePartido);
        }


        return view('liga.ligaPartidos', [
            'liga' => $liga,
            'user' => Auth::user(),
            'jornadas' => $jornadas,
            'partidos' => $partidos,
            'numJornada' => $numJornada,
            'fechaInicio' => $fechaStringInicio,
            'fechaFinal' => $fechaStringFinal,
            'jugadores' => $jugadoresPartido,
            'jugadoresPorPartido' => $jugadoresPorPartido
        ]);
    }


    public function resultado(Request $request, Ligas $liga)
    {
        $partidos = Partidos::where('id', $request->idPartido)->first();

        $jugadoresDeEstePartido = PartidoParticipaJugadores::where('partidos_id', $request->idPartido)
            ->join('jugadores', 'partido_participa_jugadores.jugadores_id', '=', 'jugadores.id')
            ->join('users', 'jugadores.user_id', '=', 'users.id')
            ->select('users.name', 'jugadores.id')
            ->get();

        switch ($liga->deporte_id) {
            case '1':
                $sets = 1;
                break;

            case '2':
                $sets = 1;
                break;

            case '3':
                $sets = 3;
                break;

            case '4':
                $sets = 3;
                break;
        }


        return view('liga.partidoResultado', [
            'liga' => $liga,
            'user' => Auth::user(),
            'partidos' => $partidos,
            'jugadores' => $jugadoresDeEstePartido,
            'sets' => $sets
        ]);
    }


    /**
     * Función para poder unirte a una liga
     */
    public function inscribirse(string $ligaId, string $userId)
    {
        try {

            $deporte = Ligas::where('id', $ligaId)
                ->select('deporte_id');

            if ($deporte = 3 || $deporte = 4) {
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
            } else {
                $jugador = Jugadores::where('user_id', $userId)
                    ->select('id');

                $equipo = JugadorTieneEquipo::where('jugadores_id', $jugador)
                    ->where('liga_id', $ligaId)
                    ->select('equipo_id');

                ParticipaEnLiga::create([
                    'liga_id' => $ligaId,
                    'equipo_id' => $equipo,
                ]);
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al inscribir al jugador: ' . $e->getMessage()]);
        }
    }

    /**
     * Almacenar el jugador que juega la jornada y que dia quiere jugarla
     */
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
            'dia_jornada' => ['array']
        ]);

        JugadorJuegaJornada::create([
            'jugador_id' => $jugador->id,
            'jornada_id' => $jornada->id,
            'dia_posible' => $validatedData['dia_jornada']
        ]);

        return redirect()->back()->with('success', 'Jugador inscrito a la próxima jornada con éxito');
    }

    /**
     * Funcíon para crear los partidos de forma automática
     */
    private function crearPartidosPorDia(string $ligaId, int $jugadoresPorPartido)
    {
        // Obtener la próxima jornada de la liga
        $fechaActual = Carbon::now();
        $jornada = Jornadas::where('liga_id', $ligaId)
            ->where('fecha-inicio', '>', $fechaActual)
            ->orderBy('fecha-inicio', 'asc')
            ->first();

        if (!$jornada) {
            return response()->json(['error' => 'No hay jornadas próximas disponibles'], 404);
        }

        // Obtener la configuración de la liga
        $liga = Ligas::find($ligaId);
        $numPistas = $liga->numPistas;

        // Calcular las horas de la liga
        $horaInicio = Carbon::parse($liga->primera_hora);
        $horaFinal = Carbon::parse($liga->ultima_hora);

        // Duración de cada partido
        $duracionPartido = 90;

        // Crear una lista de horas posibles para los partidos
        $horasPosibles = [];
        $horaActual = $horaInicio;

        while ($horaActual->lte($horaFinal)) {
            $horasPosibles[] = $horaActual->copy();
            $horaActual->addMinutes($duracionPartido); // Siguiente segmento
        }

        // Obtener todas las inscripciones para esta jornada
        $inscripciones = JugadorJuegaJornada::where('jornada_id', $jornada->id)->get();

        // Agrupar jugadores por día posible
        $jugadoresPorDia = [];

        foreach ($inscripciones as $inscripcion) {

            foreach ($inscripcion->dia_posible as $dia) {

                if (!isset($jugadoresPorDia[$dia])) {
                    $jugadoresPorDia[$dia] = [];
                }

                $jugadoresPorDia[$dia][] = $inscripcion->jugador_id;
            }
        }

        // Lista para almacenar los partidos creados
        $partidosCreados = [];

        foreach ($jugadoresPorDia as $dia => $jugadores) {
            // Mezclar jugadores para tener aleatoriedad
            shuffle($jugadores);


            foreach ($horasPosibles as $hora) {

                for ($j = 0; $j < $liga->numPistas; $j++) {

                    if (count($jugadores) >= $jugadoresPorPartido) {

                        // Crear el partido
                        $partido = Partidos::create([
                            'jornada_id' => $jornada->id,
                            'dia' => $dia,
                            'hora_inicio' => $hora->format('H:i'),
                            'hora_final' => $hora->copy()->addMinutes($duracionPartido)->format('H:i'),
                            'resultado' => "",
                            'pista' => $j + 1
                        ]);

                        for ($k = 0; $k < $jugadoresPorPartido; $k++) {

                            if (isset($jugadores[$k])) {
                                PartidoParticipaJugadores::create([
                                    'jugadores_id' => $jugadores[$k],
                                    'partidos_id' => $partido->id,
                                ]);

                                $jugador = Jugadores::find($jugadores[$k]);

                                $user = User::find($jugador->user_id);

                                if ($user) {
                                    $emailController = new EmailController();
                                    $emailController->enviarCorreoPartido($user);
                                }
                            }
                        }

                        $jugadores = array_splice($jugadores, $jugadoresPorPartido);

                        $partidosCreados[] = $partido;
                    }
                }
            }
        }
        // Resultado
        return response()->json([
            'success' => 'Partidos creados con éxito',
            'partidos' => $partidosCreados,
            'jugadoresPorPartido' => $jugadoresPorPartido
        ]);
    }


    private function comprobarFecha($fechaJornada)
    {
        $fechaJornada = Carbon::create($fechaJornada);
        $dateDiff = abs($fechaJornada->diffInDays(Carbon::now()));
        return $dateDiff < 2;
    }




    public function addResultado(Request $request, Ligas $liga, Partidos $partido)
    {
        $jugadores = $request->input('jugadores');

        // Calcular la cantidad de jugadores por cada mitad
        $jugadoresPorPareja = count($jugadores) / 2;

        // Dividir el array en dos partes iguales
        $pareja1 = array_chunk($jugadores, $jugadoresPorPareja)[0];
        $pareja2 = array_chunk($jugadores, $jugadoresPorPareja)[1];

        $jugadorID = Jugadores::where('user_id', auth()->id())
            ->first();

        $enP1 = in_array($jugadorID->id, $pareja1);
        $enP2 = in_array($jugadorID->id, $pareja2);

        switch ($liga->deporte_id) {
            case '1':
                $sets = 1;
                break;

            case '2':
                $sets = 1;
                break;

            case '3':
                $sets = 3;
                break;

            case '4':
                $sets = 3;
                break;
        }

        for ($i = 1; $i <= $sets; $i++) {
            ${"set{$i}P1"} = $request->pareja1[$i - 1];
            ${"set{$i}P2"} = $request->pareja2[$i - 1];
        }

        $puntosGanar = $liga->pnts_ganar;
        $puntosPerder = $liga->pnts_perder;
        $puntosEmpatar = $liga->pnts_empate;
        $puntosJuego = $liga->pnts_juego;

        if ($liga->deporte_id == 3 || $liga->deporte_id == 4) {
            $juegosP1 = $set1P1 + $set2P1 + $set3P1;
            $juegosP2 = $set1P2 + $set2P2 + $set3P2;
        } else {
            $juegosP1 = $set1P1;
            $juegosP2 = $set1P2;
        }

        $diferenciaJuegosP1 = $juegosP1 - $juegosP2;
        $diferenciaJuegosP2 = $juegosP2 - $juegosP1;

        if ($liga->deporte_id == 3 || $liga->deporte_id == 4) {
            $res = $this->comprobarResultadoRaquetas($sets, $liga, $puntosJuego, $diferenciaJuegosP1, $diferenciaJuegosP2, $enP1, $enP2, $puntosGanar, $puntosPerder, $set1P1, $set2P1, $set3P1,  $set1P2, $set2P2, $set3P2, $pareja1, $pareja2, $jugadoresPorPareja, $partido);
        } else {
            $res = $this->comprobarResultadoEquipos($liga, $diferenciaJuegosP1, $diferenciaJuegosP2, $enP1, $enP2, $puntosGanar, $puntosPerder, $puntosEmpatar, $set1P1, $set1P2, $pareja1, $pareja2, $jugadoresPorPareja, $partido);
        }

        if($res){
            return redirect("/liga/{$liga->id}/Partidos")->with('success', 'Resultado actualizado con éxito');
        }else{
            return redirect("/liga/{$liga->id}/Partidos")->with('error', 'El resultado no es válido');
        }
    }


    private function comprobarResultadoRaquetas(int $sets, Ligas $liga, int $puntosJuego, int $diferenciaJuegosP1, int $diferenciaJuegosP2, bool $enP1, bool $enP2, int $puntosGanar, int $puntosPerder, String $set1P1, String $set2P1, String $set3P1, String $set1P2, String $set2P2, String $set3P2, array $pareja1, array $pareja2, int $jugadoresPorPareja, Partidos $partido)
    {
        $contadorP1 = 0;
        $contadorP2 = 0;

        for ($i = 1; $i <= $sets; $i++) {

            //Comprobar set
            if (${"set{$i}P1"} == 6 && ${"set{$i}P2"} < 5) {
                //pareja1 gana
                $contadorP1++;
            } elseif (${"set{$i}P2"} == 6 && ${"set{$i}P1"} < 5) {
                //pareja2 gana
                $contadorP2++;
            } elseif (${"set{$i}P1"} == 7 && ${"set{$i}P2"} <= 6 && ${"set{$i}P2"} >= 5) {
                //pareja1 gana
                $contadorP1++;
            } elseif (${"set{$i}P2"} == 7 && ${"set{$i}P1"} <= 6 && ${"set{$i}P1"} >= 5) {
                //pareja2 gana
                $contadorP2++;
            } else {
                return false;
                //El reultado no es válido
            }

            if ($contadorP1 == 2) {
                // Pareja 1 gana
                if ($contadorP2 != 1 && ($set3P1 > 0 || $set3P2 > 0)) {
                    return false;
                }

                for ($j = 0; $j < $jugadoresPorPareja; $j++) {
                    $idJugadorP1 = $pareja1[$j];
                    $idJugadorP2 = $pareja2[$j];

                    // Actualizar información de la pareja 1 (ganadora)
                    $jugadorLigaP1 = ParticipaEnLiga::where('liga_id', $liga->id)
                        ->where('jugadores_id', $idJugadorP1)
                        ->first();

                    $partidosJugadosP1 = $jugadorLigaP1->num_partidos + 1;
                    $partidosGanados = $jugadorLigaP1->num_partidos_ganados + 1;

                    if ($puntosJuego > 0 && $diferenciaJuegosP1 > 0) {
                        $puntosP1 = $jugadorLigaP1->puntos + $puntosGanar + ($puntosJuego * $diferenciaJuegosP1);
                    } else {
                        $puntosP1 = $jugadorLigaP1->puntos + $puntosGanar;
                    }

                    $jugadorLigaP1->update([
                        'num_partidos' => $partidosJugadosP1,
                        'num_partidos_ganados' => $partidosGanados,
                        'puntos' => $puntosP1
                    ]);

                    // Actualizar información de la pareja 2 (perdedora)
                    $jugadorLigaP2 = ParticipaEnLiga::where('liga_id', $liga->id)
                        ->where('jugadores_id', $idJugadorP2)
                        ->first();

                    $partidosJugadosP2 = $jugadorLigaP2->num_partidos + 1;
                    $partidosPerdidos = $jugadorLigaP2->num_partidos_perdidos + 1;

                    if ($puntosJuego > 0 && $diferenciaJuegosP2 > 0) {
                        $puntosP2 = $jugadorLigaP2->puntos + $puntosPerder + ($puntosJuego * $diferenciaJuegosP2);
                    } else {
                        $puntosP2 = $jugadorLigaP2->puntos + $puntosPerder;
                    }

                    $jugadorLigaP2->update([
                        'num_partidos' => $partidosJugadosP2,
                        'num_partidos_perdidos' => $partidosPerdidos,
                        'puntos' => $puntosP2
                    ]);

                    // Actualizar el resultado del partido
                    $partido->update([
                        'resultado' => [$set1P1, $set1P2, $set2P1, $set2P2, $set3P1, $set3P2]
                    ]);

                    // Enviar correos electrónicos
                    if ($enP1) {
                        $this->enviarEmailResultado($pareja2, $liga);
                    }

                    if ($enP2) {
                        $this->enviarEmailResultado($pareja1, $liga);
                    }

                    // Redirigir después de procesar todos los jugadores
                    return true;
                }
            }


            if ($contadorP2 == 2) {
                // Pareja 2 gana

                if ($contadorP1 != 1 && ($set3P1 > 0 || $set3P2 > 0)) {
                    return false;
                }

                for ($j = 0; $j < $jugadoresPorPareja; $j++) {
                    $idJugadorP1 = $pareja1[$j];
                    $idJugadorP2 = $pareja2[$j];

                    // Actualizar información de la pareja 2 (ganadora)
                    $jugadorLigaP2 = ParticipaEnLiga::where('liga_id', $liga->id)
                        ->where('jugadores_id', $idJugadorP2)
                        ->first();

                    $partidosJugadosP2 = $jugadorLigaP2->num_partidos + 1;
                    $partidosGanados = $jugadorLigaP2->num_partidos_ganados + 1;

                    if ($puntosJuego > 0 && $diferenciaJuegosP2 > 0) {
                        $puntosP2 = $jugadorLigaP2->puntos + $puntosGanar + ($puntosJuego * $diferenciaJuegosP2);
                    } else {
                        $puntosP2 = $jugadorLigaP2->puntos + $puntosGanar;
                    }

                    $jugadorLigaP2->update([
                        'num_partidos' => $partidosJugadosP2,
                        'num_partidos_ganados' => $partidosGanados,
                        'puntos' => $puntosP2
                    ]);

                    // Actualizar información de la pareja 1 (perdedora)
                    $jugadorLigaP1 = ParticipaEnLiga::where('liga_id', $liga->id)
                        ->where('jugadores_id', $idJugadorP1)
                        ->first();

                    $partidosJugadosP1 = $jugadorLigaP1->num_partidos + 1;
                    $partidosPerdidos = $jugadorLigaP1->num_partidos_perdidos + 1;

                    if ($puntosJuego > 0 && $diferenciaJuegosP1 > 0) {
                        $puntosP1 = $jugadorLigaP1->puntos + $puntosPerder + ($puntosJuego * $diferenciaJuegosP1);
                    } else {
                        $puntosP1 = $jugadorLigaP1->puntos + $puntosPerder;
                    }

                    $jugadorLigaP1->update([
                        'num_partidos' => $partidosJugadosP1,
                        'num_partidos_perdidos' => $partidosPerdidos,
                        'puntos' => $puntosP1
                    ]);
                }
                // Actualizar el resultado del partido fuera del bucle
                $partido->update([
                    'resultado' => [$set1P1, $set1P2, $set2P1, $set2P2, $set3P1, $set3P2]
                ]);

                // Enviar correos electrónicos fuera del bucle
                if ($enP1) {
                    $this->enviarEmailResultado($pareja2, $liga);
                }

                if ($enP2) {
                    $this->enviarEmailResultado($pareja1, $liga);
                }

                // Redirigir después de procesar todos los jugadores
                return true;
            }
        }
    }

    private function comprobarResultadoEquipos(Ligas $liga, int $diferenciaJuegosP1, int $diferenciaJuegosP2, bool $enP1, bool $enP2, int $puntosGanar, int $puntosPerder, int $puntosEmpatar, String $set1P1, String $set1P2, array $pareja1, array $pareja2, int $jugadoresPorPareja, Partidos $partido)
    {
        if ($diferenciaJuegosP1 > $diferenciaJuegosP2) {

            for ($i = 0; $i < $jugadoresPorPareja; $i++) {
                $idJugadorP1 = $pareja1[$i];
                $idJugadorP2 = $pareja2[$i];
                $jugadorLigaP1 = ParticipaEnLiga::where('liga_id', $liga->id)
                    ->where('jugadores_id', $idJugadorP1)
                    ->first();

                $partidosJugadosP1 = $jugadorLigaP1->num_partidos + 1;
                $partidosGanados = $jugadorLigaP1->num_partidos_ganados + 1;

                $puntosP1 = $jugadorLigaP1->puntos + $puntosGanar;


                $jugadorLigaP1->update([
                    'num_partidos' => $partidosJugadosP1,
                    'num_partidos_ganados' => $partidosGanados,
                    'puntos' => $puntosP1
                ]);

                // Actualizar información de la pareja 2 (perdedora)
                $jugadorLigaP2 = ParticipaEnLiga::where('liga_id', $liga->id)
                    ->where('jugadores_id', $idJugadorP2)
                    ->first();

                $partidosJugadosP2 = $jugadorLigaP2->num_partidos + 1;
                $partidosPerdidos = $jugadorLigaP2->num_partidos_perdidos + 1;

                $puntosP2 = $jugadorLigaP2->puntos + $puntosPerder;

                $jugadorLigaP2->update([
                    'num_partidos' => $partidosJugadosP2,
                    'num_partidos_perdidos' => $partidosPerdidos,
                    'puntos' => $puntosP2
                ]);

                $partido->update([
                    'resultado' => [$set1P1, $set1P2]
                ]);

                if ($enP1) {
                    $this->enviarEmailResultado($pareja2, $liga);
                }

                if ($enP2) {
                    $this->enviarEmailResultado($pareja1, $liga);
                }
            }

            return true;
        } else if ($diferenciaJuegosP2 > $diferenciaJuegosP1) {

            for ($i = 0; $i < $jugadoresPorPareja; $i++) {
                $idJugadorP1 = $pareja1[$i];
                $idJugadorP2 = $pareja2[$i];
                $jugadorLigaP2 = ParticipaEnLiga::where('liga_id', $liga->id)
                    ->where('jugadores_id', $idJugadorP2)
                    ->first();

                $partidosJugadosP2 = $jugadorLigaP2->num_partidos + 1;
                $partidosGanados = $jugadorLigaP2->num_partidos_ganados + 1;

                $puntosP2 = $jugadorLigaP2->puntos + $puntosGanar;


                $jugadorLigaP2->update([
                    'num_partidos' => $partidosJugadosP2,
                    'num_partidos_ganados' => $partidosGanados,
                    'puntos' => $puntosP2
                ]);

                // Actualizar información de la pareja 2 (perdedora)
                $jugadorLigaP1 = ParticipaEnLiga::where('liga_id', $liga->id)
                    ->where('jugadores_id', $idJugadorP1)
                    ->first();

                $partidosJugadosP1 = $jugadorLigaP1->num_partidos + 1;
                $partidosPerdidos = $jugadorLigaP1->num_partidos_perdidos + 1;

                $puntosP1 = $jugadorLigaP1->puntos + $puntosPerder;

                $jugadorLigaP1->update([
                    'num_partidos' => $partidosJugadosP1,
                    'num_partidos_perdidos' => $partidosPerdidos,
                    'puntos' => $puntosP1
                ]);

                $partido->update([
                    'resultado' => [$set1P1, $set1P2]
                ]);

                if ($enP1) {
                    $this->enviarEmailResultado($pareja2, $liga);
                }

                if ($enP2) {
                    $this->enviarEmailResultado($pareja1, $liga);
                }
            }

            return true;
        } else {
            for ($i = 0; $i < $jugadoresPorPareja; $i++) {
                $idJugadorP1 = $pareja1[$i];
                $idJugadorP2 = $pareja2[$i];
                $jugadorLigaP2 = ParticipaEnLiga::where('liga_id', $liga->id)
                    ->where('jugadores_id', $idJugadorP2)
                    ->first();

                $partidosJugadosP2 = $jugadorLigaP2->num_partidos + 1;
                $partidosEmpatados = $jugadorLigaP2->num_partidos_empatados + 1;

                $puntosP2 = $jugadorLigaP2->puntos + $puntosEmpatar;


                $jugadorLigaP2->update([
                    'num_partidos' => $partidosJugadosP2,
                    'num_partidos_empatados' => $partidosEmpatados,
                    'puntos' => $puntosP2
                ]);

                // Actualizar información de la pareja 2 (perdedora)
                $jugadorLigaP1 = ParticipaEnLiga::where('liga_id', $liga->id)
                    ->where('jugadores_id', $idJugadorP1)
                    ->first();

                $partidosJugadosP1 = $jugadorLigaP1->num_partidos + 1;
                $partidosEmpatados = $jugadorLigaP1->num_partidos_empatados + 1;

                $puntosP1 = $jugadorLigaP1->puntos + $puntosEmpatar;

                $jugadorLigaP1->update([
                    'num_partidos' => $partidosJugadosP1,
                    'num_partidos_empatados' => $partidosEmpatados,
                    'puntos' => $puntosP1
                ]);

                $partido->update([
                    'resultado' => [$set1P1, $set1P2]
                ]);

                if ($enP1) {
                    $this->enviarEmailResultado($pareja2, $liga);
                }

                if ($enP2) {
                    $this->enviarEmailResultado($pareja1, $liga);
                }
            }

            return true;
        }
    }



    /**
     * Enviar el email del resultado
     */
    private function enviarEmailResultado(array $pareja, Ligas $liga)
    {
        foreach ($pareja as $jugador) {
            // Obtener el jugador y su usuario asociado
            $jugador = Jugadores::find($jugador);
            $user = User::find($jugador->user_id);

            // Verificar si el jugador tiene un usuario asociado
            if ($user) {
                // Enviar correo electrónico al usuario
                $emailController = new EmailController();
                $emailController->enviarCorreoResultado($user, $liga);
            }
        }
    }

    /**
     * Enviar a la vista de invitar a la liga
     */
    public function invitar(Ligas $liga)
    {
        return view('liga.invitar', [
            'liga' => $liga,
            'deportes' => Deportes::all(),
            'user' => Auth::user()
        ]);
    }

    public function enviarInvitacion(Request $request, Ligas $liga)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');

        $emailController = new EmailController();
        $emailController->enviarCorreoInvitacion($email, $liga);

        UsuarioInvitaUsuario::create([
            'user_invita' => Auth()->id(),
            'user_invitado' => $email,
        ]);

        return redirect()->route('liga.show', ['liga' => $liga->id])->with('success', 'Correo de invitación enviado.');
    }

    public function StoreEquipo(Request $request, Ligas $liga)
    {
        dd($request);

        $validatedData = $request->validate([
            'jugador1_nombre' => ['required', 'string', 'max:255'],
            'numPistas' => ['required', 'integer', 'min:0'],
            'pnts_ganar' => ['required', 'integer', 'min:0'],
            'pnts_perder' => ['required', 'integer', 'min:0'],
            'pnts_empate' => ['required', 'integer', 'min:0'],
            'pnts_juego' => ['required', 'integer', 'min:0'],
            'txt_responsabilidad' => ['required', 'string', 'max:1000'],
            'logo' => ['required', 'file', 'mimes:jpg,png,gif,jpeg'],
        ]);


        return view('liga.pagar', [
            'liga' => $liga,
            'deportes' => Deportes::all(),
            'user' => Auth::user()
        ]);
    }

    public function pagar(Ligas $liga)
    {
        return view('liga.pagar', [
            'liga' => $liga,
            'deportes' => Deportes::all(),
            'user' => Auth::user()
        ]);
    }

    public function crearEquipo(Ligas $liga)
    {
        return view('liga.crearEquipo', [
            'liga' => $liga,
            'deportes' => Deportes::all(),
            'user' => Auth::user()
        ]);
    }
}
