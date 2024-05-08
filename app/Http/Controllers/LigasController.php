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
        if ($request->precio == "") {
            $request['precio'] = 0;
        }

        //Si no hay ningún valor en el campo "pnts_juego", significa que es 0
        if ($request->pnts_juego == "") {
            $request['pnts_juego'] = 0;
        }

        // Validar la entrada del usuario con una regla de validación personalizada
        $validatedData = $request->validate([
            'nombre' => ['string', 'max:255'],
            'fecha_final' => ['date'],
            'localidad' => ['string', 'max:255'],
            'sede' => ['string', 'max:255'],
            'dia_jornada' => ['array'],
            'pnts_ganar' => ['integer', 'min:0'],
            'pnts_perder' => ['integer', 'min:0'],
            'pnts_empate' => ['nullable', 'integer', 'min:0'],
            'pnts_juego' => ['nullable', 'integer', 'min:0'],
            'txt_responsabilidad' => ['string', 'max:1000'],
            'deporte_id' => [Rule::exists('deportes', 'id')],
            'logo' => ['nullable', 'file', 'mimes:jpg,png,gif,jpeg'],
            'precio' => ['integer'],
            'posicion' => [],

            // Validación personalizada para fechas
            'fecha_fin_inscripcion' => [
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    // Validar que fecha_fin_inscripcion sea menor que fecha_inicio
                    if ($value >= $request->fecha_inicio) {
                        $fail("La fecha de fin de inscripción debe ser anterior a la fecha de inicio de la liga.");
                    }
                }
            ],
            'fecha_inicio' => [
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    // Validar que fecha_inicio sea menor que fecha_final
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

        if ($jugador) {
            $jornada = Jornadas::where('liga_id', $liga->id)->first();

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

        //Obtener la fecha de la siguiente jornada
        $fechaJornada = $this->getFechaJornada($liga);

        $fecha2Dias = $this->comprobarFecha('2024-05-10');

        $hayPartidos = Partidos::where('jornada_id', $jornada->id)
        ->exists();

        if ($fecha2Dias && !$hayPartidos) {
            switch ($liga->deporte_id) {
                case '1':
                    $this->crearPartidosPorDia($liga->id, 22);
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

                case '5':
                    $this->crearPartidosPorDia($liga->id, 2);
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
            'nombre' => ['sometimes', 'nullable', 'string', 'max:255'],
            'dia_jornada' => ['sometimes', 'nullable', 'array'],
            'pnts_ganar' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'pnts_perder' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'pnts_empate' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'pnts_juego' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'txt_responsabilidad' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'posicion' => ['sometimes', 'nullable', 'string'],
            'premio' => ['sometimes', 'nullable', 'string'],
            'logo' => ['sometimes', 'nullable', 'file', 'mimes:jpg,png,gif,jpeg'],
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
            // Obtener IDs de jugadores de la columna JSON
            $idsJugadores = json_decode($partido->jugadores, true);

            // Hacer JOIN para obtener nombres de jugadores y agregar ID del partido
            $jugadoresDeEstePartido = DB::table('jugadores')
                ->whereIn('jugadores.id', $idsJugadores) // Unir con el array de IDs
                ->join('users', 'jugadores.user_id', '=', 'users.id')
                ->select('users.name', DB::raw($partido->id . ' as partido_id')) // Almacenar ID del partido
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
        ]);
    }


    /**
     * Función para poder unirte a una liga
     */
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

            // Crear partidos mientras haya suficientes jugadores
            while (count($jugadores) >= $jugadoresPorPartido) {

                $jugadoresPartido = array_splice($jugadores, 0, $jugadoresPorPartido);

                // Crear el partido
                $partido = Partidos::create([
                    'jornada_id' => $jornada->id,
                    'dia' => $dia,
                    'jugadores' => json_encode($jugadoresPartido),
                    'resultado' => ""
                ]);

                $partidosCreados[] = $partido;
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
}
