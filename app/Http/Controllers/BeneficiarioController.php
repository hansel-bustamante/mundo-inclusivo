<?php

namespace App\Http\Controllers;

use App\Models\Beneficiario;
use App\Models\Persona; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 


class BeneficiarioController extends Controller
{
    public function index()
    {
        $beneficiarios = Beneficiario::with('persona')->get();
        
        return view('beneficiario.index', compact('beneficiarios'));
    }
    
    public function create()
    {
        // Antes: return view('beneficiario.create', compact('personas'));
        // Ahora:
        return view('beneficiario.create'); // ¡Sin pasar $personas!
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|integer|exists:persona,id_persona|unique:beneficiario,id_persona',
            'tipo_discapacidad' => 'required|string|max:100',
        ]);

        Beneficiario::create($request->all());

        return redirect()->route('beneficiario.index')->with('success', 'Beneficiario registrado exitosamente.');
    }
    public function show($id)
    {
        return Beneficiario::with('persona')->findOrFail($id);
    }
    public function edit($id)
    {
        $beneficiario = Beneficiario::with('persona')->findOrFail($id);
        return view('beneficiario.edit', compact('beneficiario'));
    }
    public function update(Request $request, $id)
    {
        $beneficiario = Beneficiario::findOrFail($id);

        $request->validate([
            'tipo_discapacidad' => 'sometimes|required|string|max:100',
        ]);

        $beneficiario->update($request->only(['tipo_discapacidad']));

        return redirect()->route('beneficiario.index')->with('success', 'Tipo de discapacidad actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $beneficiario = Beneficiario::findOrFail($id);
        $beneficiario->delete();

        return redirect()->route('beneficiario.index')->with('success', 'Beneficiario eliminado exitosamente.');
    }

    public function searchPersonas(Request $request)
    {
        $search = $request->query('q');
        
        // Si no hay término de búsqueda, devolvemos un array vacío.
        if (empty($search)) {
            return response()->json(['results' => []]);
        }
        
        // Búsqueda robusta en el servidor
        $personas = Persona::select('id_persona', 'nombre', 'apellido_paterno', 'apellido_materno', 'carnet_identidad')
            ->where(function ($query) use ($search) {
                $searchTerm = '%' . $search . '%';
                
                // 1. Buscar por Carnet de Identidad
                $query->where('carnet_identidad', 'LIKE', $searchTerm)
                      // 2. Buscar por nombre/apellidos concatenados (Suele ser la forma más flexible)
                      ->orWhere(DB::raw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'LIKE', $searchTerm)
                      // 3. Buscar por el término en los campos individuales (por si la concatenación falla)
                      ->orWhere('nombre', 'LIKE', $searchTerm)
                      ->orWhere('apellido_paterno', 'LIKE', $searchTerm)
                      ->orWhere('apellido_materno', 'LIKE', $searchTerm);
            })
            ->limit(20) // Limita el número de resultados devueltos.
            ->get();
            
        // Formatear los resultados en la estructura que Select2 espera (id y text)
        $results = $personas->map(function ($persona) {
            return [
                'id' => $persona->id_persona,
                'text' => $persona->nombre . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno . ' (C.I.: ' . $persona->carnet_identidad . ')',
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => false]
        ]);
    }
}