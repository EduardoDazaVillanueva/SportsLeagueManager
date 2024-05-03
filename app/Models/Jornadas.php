<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jornadas extends Model
{
    use HasFactory;

    protected $fillable = [
        'num_jornada',
        'fecha-inicio',
        'fecha-final',
        'liga_id',
    ];
}
