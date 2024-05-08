<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jornadas;
use App\Http\Controllers\LigasController;
use Carbon\Carbon;

class CrearPartidosPorDia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'partidos:crear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear partidos para jornadas con menos de dos días de anticipación';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Obtenemos la fecha de dos días a partir de ahora
        $fechaLimite = Carbon::now()->addDays(2);

        // Buscamos jornadas que ocurren dentro de dos días
        $jornadas = Jornadas::where('fecha-inicio', '<=', $fechaLimite)->get();

        $partidoController = new LigasController();

        foreach ($jornadas as $jornada) {
            // Llama a la función para crear partidos por día para esta jornada
            $partidoController->crearPartidosPorDia($jornada->liga_id);
        }

        $this->info('Partidos creados para jornadas dentro de dos días.');

        return 0;
    }
}
