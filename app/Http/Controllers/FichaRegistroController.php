<?php

namespace App\Http\Controllers;

use App\Models\FichaRegistro;
use App\Models\Participante; 
use App\Models\AreaIntervencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para obtener el usuario logueado
use Illuminate\Validation\Rule;

class FichaRegistroController extends Controller
{
    /**
     * Muestra la lista de todas las Fichas de Registro.
     */
    public function index()
    {
        $fichas = FichaRegistro::with(['beneficiario.persona', 'areaIntervencion', 'usuario.persona'])
                    ->orderBy('fecha_registro', 'desc')
                    ->get();
        return view('ficha_registro.index', compact('fichas'));
    }

    /**
     * Muestra el formulario para crear una nueva Ficha.
     */
    public function create()
    {
        // Obtener beneficiarios que *aún no* tienen una ficha de registro.
        $beneficiariosConFicha = FichaRegistro::pluck('beneficiario_id')->toArray();
        $beneficiarios = Participante::with('persona')
                        ->whereNotIn('id_persona', $beneficiariosConFicha)
                        ->get();
                        
        $areas = AreaIntervencion::orderBy('nombre_area')->get();
        
        // ID del usuario autenticado. Usamos 1 como fallback si no hay sesión activa.
        $usuario_id = Auth::check() ? Auth::id() : 1; 

        return view('ficha_registro.create', compact('beneficiarios', 'areas', 'usuario_id'));
    }

    /**
     * Almacena una nueva Ficha en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha_registro' => 'required|date',
            'retraso_en_desarrollo' => 'required|boolean',
            'incluido_en_educacion_2025' => 'required|boolean',
            // CRÍTICO: El beneficiario solo puede tener UNA ficha.
            'beneficiario_id' => 'required|exists:PARTICIPANTE,id_persona|unique:FICHA_REGISTRO,beneficiario_id', 
            'usuario_id' => 'required|exists:USUARIO,id_persona', // Debe ser un usuario válido
            'area_intervencion_id' => 'required|exists:AREA_INTERVENCION,codigo_area',
        ]);

        FichaRegistro::create($request->all());

        return redirect()->route('ficha_registro.index')->with('success', 'Ficha de Registro creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una Ficha.
     */
    public function edit(FichaRegistro $ficha_registro)
    {
        $areas = AreaIntervencion::orderBy('nombre_area')->get();
        
        // Solo necesitamos el beneficiario de esta ficha para mostrarlo.
        $beneficiarios = Participante::where('id_persona', $ficha_registro->beneficiario_id)
                        ->with('persona')
                        ->get();

        return view('ficha_registro.edit', compact('ficha_registro', 'beneficiarios', 'areas'));
    }

    /**
     * Actualiza una Ficha en la base de datos.
     */
    public function update(Request $request, FichaRegistro $ficha_registro)
    {
        $request->validate([
            'fecha_registro' => 'sometimes|required|date',
            'retraso_en_desarrollo' => 'sometimes|required|boolean',
            'incluido_en_educacion_2025' => 'sometimes|required|boolean',
            'area_intervencion_id' => 'sometimes|required|exists:AREA_INTERVENCION,codigo_area',
        ]);
        
        // No permitimos cambiar 'beneficiario_id' ni 'usuario_id' en el update web, 
        // ya que son claves de registro inalterables.
        $data = $request->only([
            'fecha_registro', 
            'retraso_en_desarrollo', 
            'incluido_en_educacion_2025', 
            'area_intervencion_id'
        ]);

        $ficha_registro->update($data);

        return redirect()->route('ficha_registro.index')->with('success', 'Ficha de Registro actualizada exitosamente.');
    }

    /**
     * Elimina una Ficha de la base de datos.
     */
    public function destroy(FichaRegistro $ficha_registro)
    {
        $ficha_registro->delete();
        return redirect()->route('ficha_registro.index')->with('success', 'Ficha de Registro eliminada.');
    }
}