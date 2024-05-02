<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario tiene permiso para acceder a la página
        if (!$request->user() || !$request->user()->can_access_page) {
        
            return new Response('No tienes permiso para acceder a esta página.', 403);
        }

        // Si tiene permiso, continuar con la siguiente solicitud
        return $next($request);
    }
}
