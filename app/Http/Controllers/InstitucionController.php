<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use Illuminate\Http\Request;

class InstitucionController extends Controller
{
    /**
     * Muestra la lista de instituciones y la vista principal del CRUD.
     */
    public function index()
    {
        // Traer todas las instituciones y pasarlas a la vista
        $instituciones = Institucion::all();
        // Cargar la vista de listado
        return view('institucion.index', compact('instituciones'));
    }

    /**
     * Muestra el formulario para crear una nueva institución.
     */
    public function create()
    {
        return view('institucion.create');
    }

    /**
     * Almacena una nueva institución en la base de datos (Usado por el formulario).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_institucion' => 'required|string|max:150',
            'tipo' => 'required|string|max:100',
            'direccion' => 'required|string|max:150',
            'telefono' => 'required|string|max:20',
            'municipio' => 'required|string|max:100',
        ]);

        Institucion::create($request->all());

        return redirect()->route('institucion.index')->with('success', 'Institución creada exitosamente.');
    }

    /**
     * Muestra los detalles de una institución específica. (No se usará en este CRUD simple).
     */
    public function show($id)
    {
        // No implementado en este CRUD simple
        return Institucion::findOrFail($id);
    }

    /**
     * Muestra el formulario para editar una institución.
     */
    public function edit($id)
    {
        $institucion = Institucion::findOrFail($id);
        return view('institucion.edit', compact('institucion'));
    }

    /**
     * Actualiza la institución especificada en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_institucion' => 'required|string|max:150',
            'tipo' => 'required|string|max:100',
            'direccion' => 'required|string|max:150',
            'telefono' => 'required|string|max:20',
            'municipio' => 'required|string|max:100',
        ]);
        
        $institucion = Institucion::findOrFail($id);
        $institucion->update($request->all());

        return redirect()->route('institucion.index')->with('success', 'Institución actualizada exitosamente.');
    }

    /**
     * Elimina una institución de la base de datos.
     */
    public function destroy($id)
    {
        $institucion = Institucion::findOrFail($id);
        $institucion->delete();
        
        return redirect()->route('institucion.index')->with('success', 'Institución eliminada exitosamente.');
    }
}
