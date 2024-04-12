<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ligas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'logo',
        'fecha_inicio',
        'fecha_final',
        'fecha_fin_inscripcion',
        'localidad',
        'sede',
        'dia_jornada',
        'pnts_ganar',
        'pnts_perder',
        'pnts_empate',
        'pnts_juego',
        'txt_responsabilidad',
    ];
}
