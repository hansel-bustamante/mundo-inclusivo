<?php

namespace App\Http\Controllers;

use App\Models\AreaIntervencion;
use Illuminate\Http\Request;

class AreaIntervencionController extends Controller
{
    /**
     * Muestra la lista de áreas.
     */
    public function index()
    {
        $areas = AreaIntervencion::all();
        // Devuelve la vista de listado
        return view('area.index', compact('areas')); 
    }

    /**
     * Muestra el formulario para crear una nueva área.
     */
    public function create()
    {
        // Devuelve la vista de creación
        return view('area.create');
    }

    /**
     * Almacena una nueva área en la base de datos.
     */
public function store(Request $request)
    {
        $request->validate([
            // REGLA CLAVE: Requerido, máximo 20 caracteres y DEBE SER ÚNICO
            'codigo_area' => 'required|string|max:20|unique:area_intervencion,codigo_area', 
            'nombre_area' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
        ]);

        AreaIntervencion::create($request->all());

        return redirect()->route('area.index')->with('success', 'Área de Intervención creada exitosamente.');
    }

    /**
     * Muestra los detalles de un área específica. (No implementado en el CRUD web)
     */
    public function show(AreaIntervencion $area)
    {
        // En un CRUD web, esta función a menudo se omite o redirige a la edición
        return redirect()->route('area.edit', $area);
    }

    /**
     * Muestra el formulario para editar un área específica.
     * Usa Route Model Binding con la clave primaria 'codigo_area'
     */
    public function edit(AreaIntervencion $area)
    {
        // Devuelve la vista de edición
        return view('area.edit', compact('area'));
    }

    /**
     * Actualiza un área específica en la base de datos.
     */
public function update(Request $request, AreaIntervencion $area)
    {
        $request->validate([
            // REGLA CLAVE: Requerido, y único excepto para el código actual (Route Model Binding usa $area)
            'codigo_area' => 'required|string|max:20|unique:area_intervencion,codigo_area,' . $area->codigo_area . ',codigo_area', 
            'nombre_area' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
        ]);

        $area->update($request->all());

        return redirect()->route('area.index')->with('success', 'Área de Intervención actualizada exitosamente.');
    }

    /**
     * Elimina un área específica de la base de datos.
     */
    public function destroy(AreaIntervencion $area)
    {
        $area->delete();

        // Redirecciona al índice con un mensaje de éxito
        return redirect()->route('area.index')->with('success', 'Área de Intervención eliminada exitosamente.');
    }
}