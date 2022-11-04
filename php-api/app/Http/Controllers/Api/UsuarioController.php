<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UsuarioDispositivo;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function listadoUsuarios()
    {
        return User::all();
    }

    public function dispositivos(Request $request, $idUsuario)
    {
        return UsuarioDispositivo::where('USUARIO_ID', $idUsuario)->get();
    }

}