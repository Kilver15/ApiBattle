<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\RequestLog;

class usuarioscontroller extends Controller
{   

    public function index()
    {
        DB::connection()->enableQueryLog();

        $boletos = User::all();
        $user = auth()->user();
        $userId = $user ? $user->id : null;
    
        $query = DB::getQueryLog();
        $query = end($query)['query'];
    

        $log = new RequestLog;
        $log->user = $userId;
        $log->metodo = 'GET';
        $log->url = request()->fullUrl(); 
        $log->ip = request()->ip();
        $log->agent = request()->userAgent();
        $log->timestamps = now();
        $log->query = $query; 
        $log->save();
        return response()->json($boletos, 200);
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'rol' => 'required|exists:roles,id',
        ],[
            'name.required' => 'El campo nombre es obligatorio.',
            'email.required' => 'El campo email es obligatorio.',
            'email.string' => 'El email debe ser una cadena de texto.',
            'email.email' => 'El formato del email no es válido.',
            'email.unique' => 'El email ya está registrado.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'rol.required' => 'El campo rol es obligatorio.',
            'rol.exists' => 'El rol seleccionado no existe.', 
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
            }
            $user = User::create(array_merge(
                $validator->validated(),
                ['password'=>bcrypt($request->password),
                'is_active' => 1,
                ]
            ));
        $user = auth()->user();
        $userId = $user ? $user->id : null;

        // Obtener información de la petición
        $log = new RequestLog;
        $log->user = $userId;
        $log->metodo =$request->method();
        $log->url =$request->fullUrl();
        $log->ip = $request->ip();
        $log->agent = $request->userAgent();
        $log->timestamps = now();
        $log->datos = $request->all();
        $log->save();
        return response()->json([
            'message' => 'usuario registrado correctamente. verifica tu correo para activar tu cuenta ', 'user'=>$user
        ],201);
    }

    
    public function show($id)
    {
        DB::connection()->enableQueryLog();

        $boleto = User::find($id);

        if (!$boleto) {
            return response()->json(['message' => 'usuario no encontrado'], 404);
        }
        $user = auth()->user();
        $userId = $user ? $user->id : null;
    
        $query = DB::getQueryLog();
        $query = end($query)['query'];
    

        $log = new RequestLog;
        $log->user = $userId;
        $log->metodo = 'GET';
        $log->url = request()->fullUrl(); 
        $log->ip = request()->ip();
        $log->agent = request()->userAgent();
        $log->timestamps = now();
        $log->query = $query; 
        $log->save();
        return response()->json($boleto, 200);
    }

    
    public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => [
            'required',
            'string',
            'email',
            Rule::unique('users')->ignore($id),
        ],
        'password' => 'sometimes|string|confirmed|min:6',
        'rol' => 'required|exists:roles,id',
    ], [
        'name.required' => 'El campo nombre es obligatorio.',
        'email.required' => 'El campo email es obligatorio.',
        'email.string' => 'El email debe ser una cadena de texto.',
        'email.email' => 'El formato del email no es válido.',
        'email.unique' => 'El email ya está registrado.',
        'password.required' => 'El campo contraseña es obligatorio.',
        'password.string' => 'La contraseña debe ser una cadena de texto.',
        'password.confirmed' => 'La contraseña y la confirmación de contraseña no coinciden.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        'rol.required' => 'El campo rol es obligatorio.',
        'rol.exists' => 'El rol seleccionado no existe.', 
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $usuario = User::find($id);

    if (!$usuario) {
        return response()->json(['message' => 'usuario no encontrado'], 404);
    }

    // Rehashear la contraseña si se proporcionó una nueva
    if ($request->has('password')) {
        $usuario->password = bcrypt($request->password);
    }

    // Actualizar otros campos
    $usuario->name = $request->name;
    $usuario->email = $request->email;
    $usuario->rol = $request->rol;

    $usuario->save();

    // Obtener información de la petición
    $log = new RequestLog;
    $log->user = $usuario->id;
    $log->metodo = $request->method();
    $log->url = $request->fullUrl();
    $log->ip = $request->ip();
    $log->agent = $request->userAgent();
    $log->timestamps = now();
    $log->datos = $request->all();
    $log->save();

    return response()->json(['message' => 'usuario actualizado correctamente'], 200);
}



    
    public function destroy($id)
    {
        DB::connection()->enableQueryLog();

        $boleto = User::find($id);

        if (!$boleto) {
            return response()->json(['message' => 'usuario no encontrado'], 404);
        }

        $boleto->delete();
        $user = auth()->user();
        $userId = $user ? $user->id : null;
    
        $query = DB::getQueryLog();
        $query = end($query)['query'];
    

        $log = new RequestLog;
        $log->user = $userId;
        $log->metodo = 'DELETE';
        $log->url = request()->fullUrl(); 
        $log->ip = request()->ip();
        $log->agent = request()->userAgent();
        $log->timestamps = now();
        $log->query = $query; 
        $log->save();
        return response()->json(['message' => 'usuario eliminado correctamente'], 200);
    }

    public function showroles()
    {
        $boletos = roles::all();
        return response()->json($boletos, 200);
    }
    public function logs()
    {

        $logs = RequestLog::all();


        foreach ($logs as $log) {

            $usuario = User::find($log->user);

            if ($usuario) {

                $log->nombre_usuario = $usuario->name;
                $log->correo_usuario = $usuario->email;
            } else {

                $log->nombre_usuario = 'Usuario desconocido';
                $log->correo_usuario = 'N/A';
            }
        }

        return response()->json($logs, 200);
    }

    public function activateUser(Request $request, $id)
    {
        $user = User::find($id);  
        if (!$user) {
            return response()->json(['message' => 'usuario no encontrado'], 404);
        }
        $user->can_access_page = true;
        $user->save();
        return response()->json(['message' => 'Usuario activado correctamente'], 200);
    }

    public function deactivateUser(Request $request, $id)
    {
        $user = User::find($id);  
        if (!$user) {
            return response()->json(['message' => 'usuario no encontrado'], 404);
        }
        $user->can_access_page = false;
        $user->save();
        return response()->json(['message' => 'Usuario desactivado correctamente'], 200);
    }
}
