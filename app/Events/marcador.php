<?php

namespace App\Events;

use App\Models\Genero;
use App\Models\Juego;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class marcador implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $genero;

    public function __construct(Juego $genero)
    {
        $this->genero = $genero;
    }

    public function broadcastOn()
    {
        return new Channel('genero-channel');
    }
    public function broadcastAs()
    {
        return 'marcador';
    }
}
