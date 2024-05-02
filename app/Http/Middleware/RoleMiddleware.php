<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\roles; // Importa el modelo roles

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role)
    {

            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['message' => 'Acceso no autorizado'], 403);
            }
            
            $userRole = $user->rol;
            
            // Verificar si el rol del usuario está contenido en los roles permitidos
            if (in_array($userRole, $role)) {
                return $next($request);
            }
            
            return response()->json(['message' => 'Acceso no autorizado'], 403);
    }
}
