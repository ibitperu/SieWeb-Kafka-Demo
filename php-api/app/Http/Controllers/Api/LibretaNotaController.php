<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LibretaNota;
use App\Models\UsuarioDispositivo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class LibretaNotaController extends Controller
{

    public function generarLibretaNota(Request $request, $idUsuario, $bimestre)
    {
        //1. Generamos el PDF
        //2. Almacenamos el PDF en el repositorio cloud
        //3. La Url del PDF le devolvemos al usuario
        $urlDocumento = "https://www.africau.edu/images/default/sample.pdf";

        $titulo = "Libreta de notas generado satisfactoriamente";
        $cuerpo = "El documento lo puedes descarga del siguiente enlace $$urlDocumento";

        $dispositivos = UsuarioDispositivo::where('USUARIO_ID', $idUsuario)->get();

        //notificación a un único dispositivo
        // $response = Http::withHeaders([
        //     'Authorization' => 'Key=AAAA2IEmK9E:APA91bF7yD-aVDTrcyaACO0HLKLNYNztdZh57GwaA3AXUBHi5nPiYWtuo5zgkTmpP0y0GsdFYsX4njgmamoMTviRqMRkhc43rO7oof_z1wCJPQwcQtABTCDXV8TgGSd6yIbvqSWkTrlU',
        //     'Content-Type' => 'application/json'
        // ])->post('https://fcm.googleapis.com/fcm/send', [
        //     "notification" => [
        //         "title" => $titulo,
        //         "body" => $cuerpo,
        //     ],
        //     "to" => $dispositivo->TOKEN_DISPOSITIVO
        // ]);

        $tokensDispositivos = [];
        foreach ($dispositivos as $dispositivo) {
           array_push($tokensDispositivos, $dispositivo->TOKEN_DISPOSITIVO);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Key=AAAA2IEmK9E:APA91bF7yD-aVDTrcyaACO0HLKLNYNztdZh57GwaA3AXUBHi5nPiYWtuo5zgkTmpP0y0GsdFYsX4njgmamoMTviRqMRkhc43rO7oof_z1wCJPQwcQtABTCDXV8TgGSd6yIbvqSWkTrlU',
            'Content-Type' => 'application/json'
        ])->post('https://fcm.googleapis.com/fcm/send', [
            "notification" => [
                "title" => $titulo,
                "body" => $cuerpo,
            ],
            "registration_ids" => $tokensDispositivos
        ]);


        return response()->json([
            "title" => $titulo,
            "body" => $cuerpo
        ]);
    }
}
