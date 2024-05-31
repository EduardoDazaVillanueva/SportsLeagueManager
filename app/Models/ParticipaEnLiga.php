<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipaEnLiga extends Model
{
    use HasFactory;

    protected $fillable = [
        'liga_id', 
        'jugadores_id',
        'equipo_id',
        'num_partidos',
        'num_partidos_ganados',
        'num_partidos_perdidos',
        'num_partidos_empatados',
        'puntos',
    ];

    // Relación con el modelo Ligas
    public function liga()
    {
        return $this->belongsTo(Ligas::class, 'liga_id');
    }

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(Jugadores::class, 'jugadores_id');
    }
}
