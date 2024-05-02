<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JugadorJuegaJornada extends Model
{
    use HasFactory;

    protected $fillable = [
        'jugador_id',
        'jornada_id',
        'dia_posible',
    ];

    protected $casts = [
        'dia_posible' => 'array',
    ];
}
