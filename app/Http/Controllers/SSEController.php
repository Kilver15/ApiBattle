<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SSEController extends Controller
{
    public function sendSSE()
    {
        $response = new StreamedResponse(function () {
            while (true) {
                // Obtener los cambios en los productos desde la caché
                $productos = Cache::get('productos', []);

                // Envía un evento SSE con los cambios en los productos
                echo "event: update\n";
                echo "data: " . json_encode($productos) . "\n\n";

                // Enviar el búfer de salida
                ob_flush();
                flush();

                // Esperar un tiempo antes de verificar los cambios nuevamente
                sleep(5); // Por ejemplo, verifica cada 5 segundos
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    
    }
}
