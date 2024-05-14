<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioInvitaUsuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_invita',
        'user_invitado',
    ];
}