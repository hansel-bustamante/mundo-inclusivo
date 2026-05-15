<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 1. Si está logueado Y tiene la obligación de cambiar contraseña
        if ($user && $user->must_change_password == 1) {
            
            // 2. Rutas permitidas (Para no crear un bucle infinito)
            // El usuario debe poder ver el formulario, enviar el formulario y salir.
            $rutasPermitidas = [
                'password.change', 
                'password.update', 
                'logout'
            ];

            // 3. Si intenta ir a cualquier otra ruta (como el dashboard), lo devolvemos
            if (!in_array($request->route()->getName(), $rutasPermitidas)) {
                return redirect()->route('password.change')
                    ->with('warning', 'Debe cambiar su contraseña obligatoriamente.');
            }
        }

        return $next($request);
    }
}