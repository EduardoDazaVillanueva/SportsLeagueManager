<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Partidos;
use App\Models\Jornadas;
use App\Models\Ligas;
use App\Models\Jugadores;
use App\Models\PartidoParticipaJugadores;
use App\Models\User;
use App\Models\JugadorJuegaJornada;

use App\Http\Controllers\EmailController;

use Carbon\Carbon;

class CrearPartidosPorDia extends Command
{
    protected $signature = 'partidos:comprobar';
    protected $description = 'Comprueba y crea partidos si se cumplen las condiciones';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $ligas = Ligas::all();

        foreach ($ligas as $liga) {
            $fechaJornada = $this->getFechaJornada($liga);

            if (!$fechaJornada) {
                continue; // Si no hay fecha de jornada, saltar esta liga
            }

            $jornada = Jornadas::where('liga_id', $liga->id)
                ->where('fecha-inicio', $fechaJornada)
                ->first();

            if (!$jornada) {
                continue; // Si no hay jornada, saltar esta liga
            }

            $fecha2Dias = $this->comprobarFecha($fechaJornada);
            $hayPartidos = Partidos::where('jornada_id', $jornada->id)->exists();

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
        }
    }

    private function comprobarFecha($fechaJornada)
    {
        $fechaJornada = Carbon::create($fechaJornada);
        $dateDiff = abs($fechaJornada->diffInDays(Carbon::now()));
        return $dateDiff < 2;
    }

    private function getFechaJornada(Ligas $liga)
    {
        $fechaActual = Carbon::now();
        $jornada = Jornadas::where('liga_id', $liga->id)
            ->where('fecha-inicio', '>', $fechaActual)
            ->orderBy('fecha-inicio', 'asc')
            ->first();

        if (!$jornada) {
            return null; // Si no hay próxima jornada, retornar null
        }

        $fecha = $jornada->getAttribute('fecha-inicio');
        return $fecha ? Carbon::parse($fecha)->format('Y-m-d') : null;
    }

    private function crearPartidosPorDia(string $ligaId, int $jugadoresPorPartido)
    {
        $fechaActual = Carbon::now();
        $jornada = Jornadas::where('liga_id', $ligaId)
            ->where('fecha-inicio', '>', $fechaActual)
            ->orderBy('fecha-inicio', 'asc')
            ->first();

        if (!$jornada) {
            return;
        }

        $liga = Ligas::find($ligaId);
        $numPistas = $liga->numPistas;
        $horaInicio = Carbon::parse($liga->primera_hora);
        $horaFinal = Carbon::parse($liga->ultima_hora);
        $duracionPartido = 90;

        $horasPosibles = [];
        $horaActual = $horaInicio;

        while ($horaActual->lte($horaFinal)) {
            $horasPosibles[] = $horaActual->copy();
            $horaActual->addMinutes($duracionPartido);
        }

        $inscripciones = JugadorJuegaJornada::where('jornada_id', $jornada->id)->get();
        $jugadoresPorDia = [];

        foreach ($inscripciones as $inscripcion) {
            foreach ($inscripcion->dia_posible as $dia) {
                if (!isset($jugadoresPorDia[$dia])) {
                    $jugadoresPorDia[$dia] = [];
                }
                $jugadoresPorDia[$dia][] = $inscripcion->jugador_id;
            }
        }

        $partidosCreados = [];

        foreach ($jugadoresPorDia as $dia => $jugadores) {
            shuffle($jugadores);

            foreach ($horasPosibles as $hora) {
                for ($j = 0; $j < $liga->numPistas; $j++) {
                    if (count($jugadores) >= $jugadoresPorPartido) {
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
                                    $emailController->enviarCorreoPartido($user, $liga);
                                }
                            }
                        }

                        $jugadores = array_splice($jugadores, $jugadoresPorPartido);
                        $partidosCreados[] = $partido;
                    }
                }
            }
        }

        return response()->json([
            'success' => 'Partidos creados con éxito',
            'partidos' => $partidosCreados,
            'jugadoresPorPartido' => $jugadoresPorPartido
        ]);
    }
}
