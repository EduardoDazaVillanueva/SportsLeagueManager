<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JugadoresHasEquipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'jugador_id',
        'equipo_id',
    ];
    
}
