<?php

namespace App\Http\Controllers;

use App\Events\cambiorealizado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Juego;
use App\Events\estadoPartida;
use App\Events\GeneroActualizado;
use App\Events\marcador;
use App\Events\CasillaRecibida;

class juegosController extends Controller
{   
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'jugador1' => 'required|integer',
            'jugador2' => 'nullable|integer',
            'puntuacion1' => 'nullable|integer',
            'puntuacion2' => 'nullable|integer',
            'estado' => 'nullable|string'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $juego = Juego::create([
            'jugador1' => $request->jugador1,
            'jugador2' => null,
            'puntuacion1' => $request->puntuacion1,
            'puntuacion2' => $request->puntuacion2,
            'estado' => 'en espera'
        ]);
        event(new GeneroActualizado($juego));
        $juego->save();
        return response()->json($juego, 201);
    }

    public function indexEnEspera(){
        $juegos = Juego::where('estado', 'en espera')->get();
        return response()->json($juegos, 200);
    }

    public function indexFinalizados(){
        $juegos = Juego::where('estado', 'finalizado')->get();
        return response()->json($juegos, 200);
    }

    public function show($id){
        $juego = Juego::find($id);
        if ($juego){
            return response()->json($juego, 200);
        }
        return response()->json(['message' => 'Partida no encontrada'], 404);
    }

    public function update(Request $request, $id){
        $juego = Juego::find($id);
        if ($juego){
            $validator = Validator::make($request->all(),[
                'jugador1' => 'integer',
                'jugador2' => 'integer',
                'puntuacion1' => 'integer',
                'puntuacion2' => 'integer',
                'estado' => 'string'
            ]);
            if ($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            $juego->jugador1 = $request->jugador1;
            $juego->jugador2 = $request->jugador2;
            $juego->puntuacion1 = $request->puntuacion1;
            $juego->puntuacion2 = $request->puntuacion2;
            $juego->estado = $request->estado;
            event(new estadoPartida($juego));
            $juego->save();
            return response()->json([
                'message' => 'Partida actualizada',
                'juego' => $juego], 200);
        }
        return response()->json(['message' => 'Partida no encontrada'], 404);
    }
    public function joinGame(Request $request, $id){
        $juego = Juego::find($id);
        if ($juego){
            $juego->jugador2 = $request->jugador2;
            $juego->save();
            $juego->estado = 'en proceso';
            event(new estadoPartida());
            $juego->save();
            return response()->json($juego, 200);
        }
        return response()->json(['message' => 'Partida no encontrada'], 404);
    }

    public function finishGame(Request $request, $id){
        $juego = Juego::find($id);
        if ($juego){
            $juego->ganador = $request->ganador;
            $juego->estado = 'finalizado';
            event(new GeneroActualizado($juego));
            $juego->save();
            return response()->json($juego, 200);
        }
        return response()->json(['message' => 'Partida no encontrada'], 404);
    }

    public function cancelGame($id){
        $juego = Juego::find($id);
        if ($juego){
            $juego->estado = 'cancelado';
            event(new GeneroActualizado($juego));
            $juego->save();
            return response()->json($juego, 200);
        }
        return response()->json(['message' => 'Partida no encontrada'], 404);
    }

    public function updateScore(Request $request, $id){
        $juego = Juego::find($id);
        if ($juego){
            $juego->puntuacion1 = $request->puntuacion1;
            $juego->puntuacion2 = $request->puntuacion2;
            event(new marcador($juego));
            $juego->save();
            return response()->json($juego, 200);
        }
        return response()->json(['message' => 'Partida no encontrada'], 404);
 }
 public function changescreen(){
    event(new cambiorealizado());
 }

public function getUserId($id){
    $juegos = Juego::where('jugador1', $id)->where('estado', 'finalizado')->get();
    return response()->json($juegos, 200);
}

public function receiveCasilla(Request $request){
    $casilla = $request->casilla;
    event(new CasillaRecibida($casilla));
    return response()->json($casilla, 200);
}
}