<?php

namespace App\Http\Controllers;

use App\Models\Beneficiario;
use App\Models\Persona; 
use Illuminate\Http\Request;

class BeneficiarioController extends Controller
{
    /**
     * Muestra una lista de todos los beneficiarios con su información de persona.
     * CORRECCIÓN: Ahora devuelve la vista Blade.
     */
    public function index()
    {
        $beneficiarios = Beneficiario::with('persona')->get();
        
        return view('beneficiario.index', compact('beneficiarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo beneficiario.
     */
    public function create()
    {
        return view('beneficiario.create');
    }
    
    /**
     * Almacena un nuevo beneficiario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|integer|exists:persona,id_persona|unique:beneficiario,id_persona',
            'tipo_discapacidad' => 'required|string|max:100',
        ]);

        Beneficiario::create($request->all());

        return redirect()->route('beneficiario.index')->with('success', 'Beneficiario registrado exitosamente.');
    }

    /**
     * Muestra un beneficiario específico por su id_persona.
     */
    public function show($id)
    {
        // En un CRUD web, show a menudo redirige a edit, o se usa para APIs.
        // Lo mantendremos como API-style por si lo usas en AJAX:
        return Beneficiario::with('persona')->findOrFail($id);
    }

    /**
     * Muestra el formulario para editar un beneficiario.
     */
    public function edit($id)
    {
        $beneficiario = Beneficiario::with('persona')->findOrFail($id);
        return view('beneficiario.edit', compact('beneficiario'));
    }

    /**
     * Actualiza la información de un beneficiario.
     */
    public function update(Request $request, $id)
    {
        $beneficiario = Beneficiario::findOrFail($id);

        $request->validate([
            'tipo_discapacidad' => 'sometimes|required|string|max:100',
        ]);

        $beneficiario->update($request->only(['tipo_discapacidad']));

        return redirect()->route('beneficiario.index')->with('success', 'Tipo de discapacidad actualizado exitosamente.');
    }

    /**
     * Elimina un beneficiario.
     */
    public function destroy($id)
    {
        $beneficiario = Beneficiario::findOrFail($id);
        $beneficiario->delete();

        return redirect()->route('beneficiario.index')->with('success', 'Beneficiario eliminado exitosamente.');
    }
}