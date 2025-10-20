<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;

class PersonaController extends Controller
{
    // Listar todas las personas
    public function index()
    {
        return response()->json(Persona::all());
    }

    // Crear una nueva persona
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'carnet_identidad' => 'required|string|max:20',
            'celular' => 'nullable|string|max:20',
            'procedencia' => 'required|string|max:100',
            'genero' => 'required|in:M,F',
        ]);

        $persona = Persona::create($request->all());

        return response()->json($persona, 201);
    }

    // Mostrar una persona por ID
    public function show($id)
    {
        $persona = Persona::findOrFail($id);
        return response()->json($persona);
    }

    // Actualizar una persona
    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'apellido_paterno' => 'sometimes|required|string|max:100',
            'apellido_materno' => 'sometimes|required|string|max:100',
            'fecha_nacimiento' => 'sometimes|required|date',
            'carnet_identidad' => 'sometimes|required|string|max:20',
            'celular' => 'nullable|string|max:20',
            'procedencia' => 'sometimes|required|string|max:100',
            'genero' => 'sometimes|required|in:M,F',
        ]);

        $persona->update($request->all());

        return response()->json($persona);
    }

    // Eliminar una persona
    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);
        $persona->delete();

        return response()->json(['message' => 'Persona eliminada correctamente']);
    }
}