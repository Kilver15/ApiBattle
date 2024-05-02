<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class LimitedAccessTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            // Intenta obtener el token del request
            $token = JWTAuth::parseToken();
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        // Verifica si el token tiene la capacidad 'limited-access'
        if (!$token->check('limited-access')) {
            return response()->json(['error' => 'No tienes permiso para acceder a esta ruta'], 403);
        }

        // Solo permitir acceso a la ruta 'verify-code'
        if ($request->route()->getName() == 'verifyCode') {
            return $next($request);
        }

        return response()->json(['error' => 'No tienes permiso para acceder a esta ruta'], 403);
    }
}
