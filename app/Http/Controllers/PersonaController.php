<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    /**
     * Muestra una lista de todas las Personas.
     */
    public function index()
    {
        // Se carga la relación con Usuario y Beneficiario para mostrar su estado.
        $personas = Persona::with(['beneficiario', 'usuario'])->orderBy('apellido_paterno')->get();
        return view('persona.index', compact('personas'));
    }

    /**
     * Muestra el formulario para crear una nueva Persona.
     */
    public function create()
    {
        return view('persona.create');
    }

    /**
     * Almacena una nueva Persona.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'carnet_identidad' => 'nullable|string|max:20|unique:persona,carnet_identidad',
            'celular' => 'nullable|string|max:20',
            'procedencia' => 'nullable|string|max:100',
            'genero' => 'required|in:M,F',
        ]);

        Persona::create($request->all());

        return redirect()->route('persona.index')->with('success', 'Persona registrada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una Persona.
     */
    public function edit($id)
    {
        $persona = Persona::findOrFail($id);
        return view('persona.edit', compact('persona'));
    }

    /**
     * Actualiza la información de una Persona.
     */
    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        $rules = [
            'nombre' => 'sometimes|required|string|max:100',
            'apellido_paterno' => 'sometimes|required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'sometimes|required|date',
            // El CI debe ser único, excluyendo el registro actual.
            'carnet_identidad' => 'nullable|string|max:20|unique:persona,carnet_identidad,'.$persona->id_persona.',id_persona',
            'celular' => 'nullable|string|max:20',
            'procedencia' => 'nullable|string|max:100',
            'genero' => 'sometimes|required|in:M,F',
        ];

        $persona->update($request->validate($rules));

        return redirect()->route('persona.index')->with('success', 'Información de Persona actualizada exitosamente.');
    }

    /**
     * Elimina una Persona.
     */
    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);

        // Validar que no tenga registros dependientes antes de eliminar
        if ($persona->beneficiario || $persona->usuario) {
            return back()->with('error', 'No se puede eliminar la persona porque tiene registros asociados como Beneficiario o Usuario.');
        }

        $persona->delete();

        return redirect()->route('persona.index')->with('success', 'Persona eliminada exitosamente.');
    }
}