<?php

namespace App\Listeners;

use App\Events\ProductoCreado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Symfony\Component\HttpFoundation\StreamedResponse;


class EnviarEventoSSE
{
    public function handle(ProductoCreado $event)
    {
        $producto = $event->producto;
        $message = "Nuevo producto creado: {$producto->nombre}";

        $eventStream = new StreamedResponse();
        $eventStream->headers->set('Content-Type', 'text/event-stream');
        $eventStream->headers->set('Cache-Control', 'no-cache');
        $eventStream->headers->set('X-Accel-Buffering', 'no');
        $eventStream->setCallback(function () use ($message) {
            echo "data: $message\n\n";
            flush();
        });

        return $eventStream;
    }
}
