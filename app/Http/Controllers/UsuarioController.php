<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Persona;
use App\Models\AreaIntervencion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password; // <--- AGREGAR ESTO

class UsuarioController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index()
    {
        $usuarios = Usuario::with(['persona', 'areaIntervencion'])->get();
        return view('usuario.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create(Request $request)
    {
        // 1. Obtener Áreas para el select con FILTRO M6
        // Regla: Mostrar todas las áreas EXCEPTO las derivadas de M6 (M6A, M6B...)
        // Se permite 'M6' exacto y cualquier otra que no empiece por 'M6'
        $areas = AreaIntervencion::where(function($query) {
            $query->where('codigo_area', 'NOT LIKE', 'M6%') // Trae M1, M2, M3...
                  ->orWhere('codigo_area', '=', 'M6');      // Trae explícitamente la central M6
        })->orderBy('nombre_area')->get();

        // 2. Obtener Personas disponibles (que NO tienen usuario todavía)
        $personas = Persona::whereNotIn('id_persona', Usuario::pluck('id_persona'))
                            ->orderBy('apellido_paterno')
                            ->get();

        // Capturamos el id_persona que viene por parámetro de consulta
        $idPersonaPrecargada = $request->query('id_persona');
        
        // Si el idPersonaPrecargada es válido y existe, lo incluimos en la colección
        if ($idPersonaPrecargada && !$personas->contains('id_persona', $idPersonaPrecargada)) {
            $personaParaPrecarga = Persona::find($idPersonaPrecargada);
            if ($personaParaPrecarga) {
                $personas->prepend($personaParaPrecarga);
            }
        }
        
        return view('usuario.create', compact('areas', 'personas', 'idPersonaPrecargada'));
    }

    /**
     * Almacena un nuevo usuario.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_persona' => 'required|exists:PERSONA,id_persona|unique:USUARIO,id_persona',
            'nombre_usuario' => 'required|string|max:50|unique:USUARIO,nombre_usuario',
            'contrasena' => [
                'required',
                'string',
                Password::min(8) // Mínimo 8 caracteres
                    ->mixedCase() // Requiere mayúsculas y minúsculas
                    ->numbers()   // Requiere números
                    ->symbols()   // Requiere símbolos (Opcional, bórralo si es mucho)
            ],
            'rol' => 'required|in:admin,coordinador,registrador',
            'correo' => 'required|email|max:100|unique:USUARIO,correo',
            'area_intervencion_id' => 'required|exists:AREA_INTERVENCION,codigo_area',
        ]);

        // Hash de la contraseña antes de guardar
        // Nota: Si tu modelo Usuario tiene un Mutator (setContrasenaAttribute), 
        // asegúrate de que maneje el hash correctamente para no duplicarlo.
        $validatedData['contrasena'] = Hash::make($validatedData['contrasena']);

        Usuario::create($validatedData);

        return redirect()->route('usuario.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Usuario $usuario)
    {
        // Aplicamos el mismo filtro en edición para consistencia
        $areas = AreaIntervencion::where(function($query) {
            $query->where('codigo_area', 'NOT LIKE', 'M6%')
                  ->orWhere('codigo_area', '=', 'M6');
        })->orderBy('nombre_area')->get();

        return view('usuario.edit', compact('usuario', 'areas'));
    }

    /**
     * Actualiza un usuario.
     */
/**
     * Actualiza un usuario existente.
     */
    public function update(Request $request, $id)
    {
        // 1. Reglas de validación
        // Nota: Agregué 'ignore' al email y usuario para que no de error si no los cambias
        $rules = [
            'nombre_usuario' => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique('USUARIO', 'nombre_usuario')->ignore($id, 'id_persona')
            ],
            'contrasena' => [
                'nullable', // Permitimos null para no cambiarla
                'string',
                \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers() // Tu regla fuerte
            ],
            'rol' => 'required|in:admin,coordinador,registrador',
            'correo' => [
                'required', 
                'email', 
                'max:100', 
                Rule::unique('USUARIO', 'correo')->ignore($id, 'id_persona')
            ],
            'area_intervencion_id' => 'required|exists:area_intervencion,codigo_area',
        ];

        $validatedData = $request->validate($rules);

        $usuario = Usuario::findOrFail($id);

        // 2. LÓGICA CORREGIDA PARA LA CONTRASEÑA
        // Si el campo está vacío o es null, lo quitamos del array para que NO se actualice.
        if (empty($validatedData['contrasena'])) {
            unset($validatedData['contrasena']);
        } 
        // CASO CONTRARIO: Lo dejamos tal cual (texto plano).
        // NO usamos Hash::make() aquí. Tu modelo Usuario.php ya tiene un 
        // setContrasenaAttribute que lo encriptará automáticamente al guardar.

        // 3. Actualizar
        $usuario->update($validatedData);

        return redirect()->route('usuario.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Elimina un usuario.
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuario.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    public function adminResetPassword($id)
    {
        // 1. Seguridad
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'Acceso denegado.');
        }

        try {
            // 2. Buscar el usuario
            $usuario = Usuario::findOrFail($id);

            // 3. Resetear contraseña
            // Usamos la columna real 'contrasena'.
            // Al pasar texto plano, el Mutator del modelo (si existe) o Hash::make deben actuar.
            // Aquí asumimos consistencia con el método store/update.
            $usuario->contrasena = '123456'; 
            
            // 4. Activar la bandera de cambio obligatorio
            $usuario->must_change_password = 1; 
            
            $usuario->save();

            return back()->with('success', 'Contraseña restablecida a "123456". El usuario deberá cambiarla al entrar.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al resetear: ' . $e->getMessage());
        }
    }
}