<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JugadorTieneEquipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'jugadores_id',
        'equipo_id',
        'liga_id'
    ];
    
}
