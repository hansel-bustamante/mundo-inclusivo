<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password; // <--- IMPORTANTE: Importar esta clase

class PasswordController extends Controller
{
    /**
     * Muestra el formulario para cambiar la contraseña obligatoriamente.
     */
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    /**
     * Procesa el cambio de contraseña del usuario.
     */
    public function updatePassword(Request $request)
    {
        // 1. Validación Fuerte
        $request->validate([
            'password' => [
                'required',
                'string',
                'confirmed', // Valida confirmación
                Password::min(8) // Mínimo 8 caracteres
                    ->mixedCase() // Requiere mayúsculas y minúsculas
                    ->numbers()   // Requiere al menos un número
                    // ->symbols() // (Opcional) Descomenta si quieres exigir símbolos (!@#)
            ],
        ], [
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            // Mensajes específicos para las reglas de seguridad
            'password.mixed' => 'La contraseña debe incluir letras mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
        ]);

        $user = Auth::user();

        // 2. Actualizar contraseña
        // El modelo Usuario ya tiene el mutator 'setContrasenaAttribute' que hace el bcrypt.
        $user->contrasena = $request->password; 
        
        $user->must_change_password = 0; // Quitamos la obligación
        $user->save();

        // 3. Redireccionar según el rol
        if ($user->rol == 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Contraseña actualizada y segura.');
        } elseif ($user->rol == 'registrador') {
            return redirect()->route('registrador.dashboard')->with('success', 'Contraseña actualizada y segura.');
        } elseif ($user->rol == 'coordinador') {
            return redirect()->route('coordinador.dashboard')->with('success', 'Contraseña actualizada y segura.');
        }

        // Fallback
        return redirect('/')->with('success', 'Contraseña actualizada.');
    }
}