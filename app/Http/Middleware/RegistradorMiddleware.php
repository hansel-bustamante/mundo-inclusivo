<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RegistradorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Comprueba que el usuario esté autenticado Y su rol sea 'registrador'
        if (Auth::check() && Auth::user()->rol === 'registrador') { 
            return $next($request);
        }

        // Si falla la autorización, redirigir
        Auth::logout(); // Opcional: Cerrar sesión si intenta acceder sin permiso
        return redirect('/login')->with('error', 'Acceso denegado. Se requiere el rol de Registrador.');
    }
}