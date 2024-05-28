<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioCompraProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fecha_compra',
        'producto_id'
    ];
}
