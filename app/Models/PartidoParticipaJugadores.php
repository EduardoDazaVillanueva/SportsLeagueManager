<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidoParticipaJugadores extends Model
{
    use HasFactory;

    protected $fillable = [
        'jugadores_ids',
        'partidos_id',
    ];
}
