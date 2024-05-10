<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partidos extends Model
{
    use HasFactory;

    protected $fillable = [
        'dia',
        'resultado',
        'jornada_id',
        'hora_inicio',
        'hora_final',
        'pista'
    ];
}
