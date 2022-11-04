<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioDispositivo extends Model
{
    use HasFactory;
    protected $table = "USUARIOS_DISPOSITIVOS";

    public $incrementing = false;
    protected $primaryKey = "TOKEN_DISPOSITIVO";
    protected $keyType = 'string';
}
