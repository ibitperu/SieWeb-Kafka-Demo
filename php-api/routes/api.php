<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LibretaNotaController;
use App\Http\Controllers\Api\UsuarioController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('usuarios', [UsuarioController::class, 'listadoUsuarios']);
Route::get('usuarios/{idUsuario}/dispositivos', [UsuarioController::class, 'dispositivos']);
Route::get('usuarios/{idUsuario}/libreta-notas/{bimestre}/pdf', [LibretaNotaController::class, 'generarLibretaNota']);