<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLoginForm()
    {
        // Si el usuario ya está autenticado, lo redirigimos a su dashboard
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }
        return view('auth.login'); // Asume que tienes una vista en resources/views/auth/login.blade.php
    }

    /**
     * Maneja el intento de inicio de sesión.
     */
    public function login(Request $request)
    {
        // 1. Validar los datos de entrada
        $credentials = $request->validate([
            // Usamos 'nombre_usuario' como campo de login
            'nombre_usuario' => 'required|string', 
            'contrasena' => 'required|string',
        ]);

        // 2. Intentar autenticación
        // El guard de Laravel buscará en el campo 'nombre_usuario' y verificará 'contrasena'
        if (Auth::attempt(['nombre_usuario' => $credentials['nombre_usuario'], 'password' => $credentials['contrasena']])) {
            
            $request->session()->regenerate();

            // 3. Autenticación exitosa: Redirigir según el rol
            return $this->redirectToDashboard();
        }

        // 4. Autenticación fallida
        return back()->withErrors([
            'nombre_usuario' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('nombre_usuario');
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Determina la redirección del usuario basado en su rol.
     */
    protected function redirectToDashboard()
    {
        $user = Auth::user();

        switch ($user->rol) {
            case 'admin':
                return redirect()->route('admin.dashboard'); // Ruta protegida principal
            case 'coordinador':
                return redirect()->route('coordinador.dashboard');
            case 'registrador':
                return redirect()->route('registrador.dashboard');
            default:
                // Si el rol no está definido, simplemente redirigir a casa o logout
                return redirect('/');
        }
    }
}
