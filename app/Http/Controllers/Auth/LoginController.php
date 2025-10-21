<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Maneja la solicitud de inicio de sesión.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // 1. Validar los campos de entrada
        $request->validate([
            'nombre_usuario' => 'required|string',
            'contrasena' => 'required|string',
        ]);

        // 2. Intentar autenticar al usuario
        // Nota: Auth::attempt buscará el usuario por 'nombre_usuario' y hasheará 'contrasena' 
        // automáticamente para compararla con la base de datos.
        if (Auth::attempt(['nombre_usuario' => $request->nombre_usuario, 'password' => $request->contrasena])) {
            
            $request->session()->regenerate();
            $user = Auth::user();

            // 3. Redireccionar según el rol (Autorización)
            if ($user->rol === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->rol === 'coordinador') {
                return redirect()->intended('/coordinador/dashboard');
            } elseif ($user->rol === 'registrador') {
                return redirect()->intended('/registrador/dashboard');
            }
        }

        // 4. Fallo de autenticación
        return back()->withErrors([
            'nombre_usuario' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('nombre_usuario');
    }

    /**
     * Cierra la sesión del usuario.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Has cerrado sesión correctamente.');
    }
}
