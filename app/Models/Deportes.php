<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deportes extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
    ];
}
