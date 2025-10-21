<?php

namespace App\Http\Controllers;

use App\Models\CodigoActividad;
use Illuminate\Http\Request;

class CodigoActividadController extends Controller
{
    /**
     * Muestra la lista de códigos de actividad.
     */
    public function index()
    {
        $codigos = CodigoActividad::all();
        // Carga la vista de listado
        return view('codigo.index', compact('codigos')); 
    }

    /**
     * Muestra el formulario para crear un nuevo código.
     */
    public function create()
    {
        // Carga la vista de creación
        return view('codigo.create');
    }

    /**
     * Almacena un nuevo código en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            // CORRECCIÓN: Permite 1 o 2 caracteres
            'codigo_actividad' => 'required|string|min:1|max:2|unique:codigo_actividad,codigo_actividad', 
            'nombre_actividad' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        CodigoActividad::create($request->all());

        // Redirige a la lista
        return redirect()->route('codigo.index')->with('success', 'Código de Actividad creado exitosamente.');
    }
    
    /**
     * Muestra el formulario para editar un código específico.
     */
    public function edit(CodigoActividad $codigo)
    {
        // Carga la vista de edición
        return view('codigo.edit', compact('codigo'));
    }

    /**
     * Actualiza un código específico en la base de datos.
     */
    public function update(Request $request, CodigoActividad $codigo)
    {
        $request->validate([
            // CORRECCIÓN: Permite 1 o 2 caracteres
            'codigo_actividad' => 'required|string|min:1|max:2|unique:codigo_actividad,codigo_actividad,' . $codigo->codigo_actividad . ',codigo_actividad', 
            'nombre_actividad' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $codigo->update($request->all());

        // Redirige a la lista
        return redirect()->route('codigo.index')->with('success', 'Código de Actividad actualizado exitosamente.');
    }

    /**
     * Elimina un código específico de la base de datos.
     */
    public function destroy(CodigoActividad $codigo)
    {
        $codigo->delete();

        // Redirige a la lista
        return redirect()->route('codigo.index')->with('success', 'Código de Actividad eliminado exitosamente.');
    }
}