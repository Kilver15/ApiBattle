<?php

namespace App\Http\Controllers;

use App\Events\FuncionActualizada;
use App\Events\GeneroActualizado;
use App\Http\Controllers\Controller;
use App\Models\Genero;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GenerosController extends Controller
{
    public function index()
    {
        // Habilitar el registro de consultas SQL
        DB::connection()->enableQueryLog();
    
        $cines = Genero::all();
        $user = auth()->user();
        $userId = $user ? $user->id : null;
    
        // Obtener el query ejecutado
        $query = DB::getQueryLog();
        $query = end($query)['query'];
    
        // Crear un registro en el registro de solicitudes
        $log = new RequestLog;
        $log->user = $userId;
        $log->metodo = 'GET'; // Método GET para la operación de visualización
        $log->url = request()->fullUrl(); // URL actual
        $log->ip = request()->ip(); // IP del cliente
        $log->agent = request()->userAgent(); // Agente del usuario
        $log->timestamps = now(); // Marca de tiempo actual
        $log->query = $query; // Query ejecutado
        $log->save();
    
        return response()->json($cines, 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nombre' => 'required'
        ],[
            'nombre.required'=>'el campo nombre es obligatorio'
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $genero = Genero::create($request->all());
        event(new GeneroActualizado($genero));
        $user = auth()->user();
        $userId = $user ? $user->id : null;

        // Obtener información de la petición
        $log = new RequestLog();
        $log->user = $userId;
        $log->metodo =$request->method();
        $log->url =$request->fullUrl();
        $log->ip = $request->ip();
        $log->agent = $request->userAgent();
        $log->timestamps = now();
        $log->query = $request->all();
        $log->save();
        return response()->json(['message'=>'genero creado correctamente'],201);
    }

    public function show($id)
    {
        DB::connection()->enableQueryLog();
        $Genero = Genero::find($id);

        if (!$Genero) {
            return response()->json(['message' => 'Genero no encontrado'], 404);
        }
        $user = auth()->user();
        $userId = $user ? $user->id : null;
 $query = DB::getQueryLog();
 $query = end($query)['query'];


 $log = new RequestLog;
 $log->user = $userId;
 $log->metodo = 'GET'; // Método GET para la operación de visualización
 $log->url = request()->fullUrl(); // URL actual
 $log->ip = request()->ip(); // IP del cliente
 $log->agent = request()->userAgent(); // Agente del usuario
 $log->timestamps = now(); // Marca de tiempo actual
 $log->query = $query; // Query ejecutado
 $log->save();
        return response()->json($Genero, 200);
    }

    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nombre' => 'required'
        ],[
            'nombre.required'=>'el campo nombre es obligatorio'
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $Genero = Genero::find($id);

        if (!$Genero) {
            return response()->json(['message' => 'Genero no encontrado'], 404);
        }
        
        $Genero->update($request->all());
        event(new GeneroActualizado($Genero));
        $user = auth()->user();
        $userId = $user ? $user->id : null;

        // Obtener información de la petición
        $log = new RequestLog();
        $log->user = $userId;
        $log->metodo =$request->method();
        $log->url =$request->fullUrl();
        $log->ip = $request->ip();
        $log->agent = $request->userAgent();
        $log->timestamps = now();
        $log->query = $request->all();
        $log->save();

        return response()->json(['message' => 'Genero actualizado correctamente'], 200);
    }

    
    public function destroy($id)
    {
        $Genero = Genero::find($id);
        DB::connection()->enableQueryLog();
        event(new GeneroActualizado($Genero));
        if (!$Genero) {
            return response()->json(['message' => 'Genero no encontrado'], 404);
        }

        $Genero->delete();
        event(new FuncionActualizada($Genero));
        $user = auth()->user();
        $userId = $user ? $user->id : null;
        $query = DB::getQueryLog();
        $query = end($query)['query'];


 $log = new RequestLog;
 $log->user = $userId;
 $log->metodo = 'DELETE'; // Método GET para la operación de visualización
 $log->url = request()->fullUrl(); // URL actual
 $log->ip = request()->ip(); // IP del cliente
 $log->agent = request()->userAgent(); // Agente del usuario
 $log->timestamps = now(); // Marca de tiempo actual
 $log->query = $query; // Query ejecutado
 $log->save();
        return response()->json(['message' => 'Genero eliminado correctamente'], 200);
    }
}
