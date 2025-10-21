<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificar si el usuario está autenticado
        if (!Auth::check()) {
            // Si no está logueado, lo enviamos a la página de login
            return redirect()->route('login'); 
        }

        $user = Auth::user();

        // 2. Verificar el rol del usuario
        if ($user->rol !== 'admin') {
            // Si no es admin, redirigir a una página de acceso denegado o a su propio dashboard
            // Redirigimos a la raíz con un mensaje de error
            return redirect('/')->with('error', 'Acceso denegado. Se requiere rol de Administrador.');
        }

        // 3. Si es admin, permitir el acceso a la ruta solicitada
        return $next($request);
    }
}
