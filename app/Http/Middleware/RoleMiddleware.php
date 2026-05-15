<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Maneja la petición.
     * Los roles permitidos vienen después de $next, separados por coma.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Si el usuario tiene uno de los roles permitidos, pasa.
        if (in_array($user->rol, $roles)) {
            return $next($request);
        }

        // Si no tiene permiso, abortamos con error 403 (Prohibido) o redirigimos
        abort(403, 'No tienes autorización para entrar a esta sección.');
    }
}