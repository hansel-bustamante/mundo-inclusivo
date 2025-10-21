<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Persona;
use App\Models\AreaIntervencion;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios con su información de persona y área.
     */
    public function index()
    {
        $usuarios = Usuario::with(['persona', 'areaIntervencion'])->get();
        return view('usuario.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     * Necesitamos pasar las Áreas de Intervención para el campo SELECT.
     */
    public function create()
    {
        $areas = AreaIntervencion::orderBy('nombre_area')->get();
        return view('usuario.create', compact('areas'));
    }

    /**
     * Almacena un nuevo usuario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|integer|exists:persona,id_persona|unique:usuario,id_persona',
            'nombre_usuario' => 'required|string|max:50|unique:usuario,nombre_usuario',
            'contrasena' => 'required|string|min:6',
            'rol' => 'required|in:admin,coordinador,registrador',
            'correo' => 'required|email|max:100|unique:usuario,correo',
            // area_intervencion_id debe existir en la tabla area_intervencion en la columna codigo_area
            'area_intervencion_id' => 'required|exists:area_intervencion,codigo_area',
        ]);

        Usuario::create($request->all());

        return redirect()->route('usuario.index')->with('success', 'Usuario registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un usuario.
     */
    public function edit($id)
    {
        $usuario = Usuario::with(['persona', 'areaIntervencion'])->findOrFail($id);
        $areas = AreaIntervencion::orderBy('nombre_area')->get();
        
        return view('usuario.edit', compact('usuario', 'areas'));
    }

    /**
     * Actualiza la información de un usuario.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        
        $rules = [
            'nombre_usuario' => 'sometimes|required|string|max:50|unique:usuario,nombre_usuario,' . $usuario->id_persona . ',id_persona',
            // Contraseña opcional: Solo se requiere si se proporciona un valor
            'contrasena' => 'nullable|string|min:6', 
            'rol' => 'sometimes|required|in:admin,coordinador,registrador',
            'correo' => 'sometimes|required|email|max:100|unique:usuario,correo,' . $usuario->id_persona . ',id_persona',
            'area_intervencion_id' => 'sometimes|required|exists:area_intervencion,codigo_area',
        ];
        
        $validatedData = $request->validate($rules);
        
        // Si la contraseña está vacía, la quitamos para que el Mutator no la hashee como vacía
        if (empty($validatedData['contrasena'])) {
            unset($validatedData['contrasena']);
        }

        $usuario->update($validatedData);

        return redirect()->route('usuario.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Elimina un usuario (solo el registro de USUARIO, NO la Persona).
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuario.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}

    


