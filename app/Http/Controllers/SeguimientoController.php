<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SeguimientoController extends Controller
{
    /**
     * Muestra la lista de todos los Seguimientos.
     */
    public function index()
    {
        $seguimientos = Seguimiento::with('actividad')->orderBy('fecha', 'desc')->get();
        return view('seguimiento.index', compact('seguimientos'));
    }

    /**
     * Muestra el formulario para crear un nuevo Seguimiento.
     */
    public function create()
    {
        // Se listan las actividades para asociar el seguimiento
        $actividades = Actividad::orderBy('fecha', 'desc')->get();
        return view('seguimiento.create', compact('actividades'));
    }

    /**
     * Almacena un nuevo Seguimiento en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            // Valida que el campo 'tipo' sea uno de los valores definidos
            'tipo' => ['required', 'string', 'max:50', Rule::in(['Visita Domiciliaria', 'Llamada Telefónica', 'Reunión Presencial', 'Otro'])],
            'observaciones' => 'required|string|max:1000',
            'actividad_id' => 'required|exists:ACTIVIDAD,id_actividad',
        ]);

        Seguimiento::create($request->all());

        return redirect()->route('seguimiento.index')->with('success', 'Seguimiento registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un Seguimiento existente.
     */
    public function edit(Seguimiento $seguimiento)
    {
        $actividades = Actividad::orderBy('fecha', 'desc')->get();
        return view('seguimiento.edit', compact('seguimiento', 'actividades'));
    }

    /**
     * Actualiza un Seguimiento en la base de datos.
     */
    public function update(Request $request, Seguimiento $seguimiento)
    {
        $request->validate([
            'fecha' => 'sometimes|required|date',
            'tipo' => ['sometimes', 'required', 'string', 'max:50', Rule::in(['Visita Domiciliaria', 'Llamada Telefónica', 'Reunión Presencial', 'Otro'])],
            'observaciones' => 'sometimes|required|string|max:1000',
            'actividad_id' => 'sometimes|required|exists:ACTIVIDAD,id_actividad',
        ]);

        $seguimiento->update($request->all());

        return redirect()->route('seguimiento.index')->with('success', 'Seguimiento actualizado exitosamente.');
    }

    /**
     * Elimina un Seguimiento de la base de datos.
     */
    public function destroy(Seguimiento $seguimiento)
    {
        $seguimiento->delete();
        return redirect()->route('seguimiento.index')->with('success', 'Seguimiento eliminado.');
    }
}