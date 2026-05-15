<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Beneficiario; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Participante;
use Illuminate\Support\Facades\Auth; 

class PersonaController extends Controller
{
    /**
     * Muestra una lista de Personas.
     * REGLA DE NEGOCIO: Si soy Registrador, solo veo gente de MI área.
     */
    public function index(Request $request)
    {
        $usuarioActual = Auth::user();
        $busqueda = $request->input('busqueda');

        // 1. Iniciar consulta
        $query = Persona::with(['beneficiario', 'usuario']);

        // 2. SEGURIDAD: FILTRO POR ÁREA
        // Si el usuario NO es Admin, forzamos el filtro por su área.
        if ($usuarioActual->rol !== 'admin') {
            // Si el usuario no tiene área asignada (null), no verá nada (seguridad por defecto)
            $query->where('area_intervencion_id', $usuarioActual->area_intervencion_id);
        }

        // 3. BUSCADOR
        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('nombre', 'LIKE', "%{$busqueda}%")
                  ->orWhere('apellido_paterno', 'LIKE', "%{$busqueda}%")
                  ->orWhere('apellido_materno', 'LIKE', "%{$busqueda}%")
                  ->orWhere('carnet_identidad', 'LIKE', "%{$busqueda}%");
            });
        }

        // 4. Obtener resultados
        $personas = $query->orderBy('id_persona', 'desc')->paginate(15);

        // 5. Verificar si ya son beneficiarios (para el botón "Vincular")
        $idsEnPantalla = $personas->pluck('id_persona');
        $beneficiariosIds = Beneficiario::whereIn('id_persona', $idsEnPantalla)
                                        ->pluck('id_persona')
                                        ->toArray();

        $personas->getCollection()->transform(function ($persona) use ($beneficiariosIds) {
            $persona->is_beneficiario = in_array($persona->id_persona, $beneficiariosIds);
            return $persona;
        });

        return view('persona.index', compact('personas', 'busqueda'));
    }

    public function create()
    {
        return view('persona.create');
    }

    /**
     * Guarda la persona ASIGNÁNDOLE automáticamente el área del usuario creador.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'carnet_identidad' => 'required|string|max:20|unique:PERSONA,carnet_identidad',
            'celular' => 'nullable|string|max:20',
            'procedencia' => 'nullable|string|max:100',
            'genero' => 'required|in:M,F',
        ]);

        // 2. INYECCIÓN AUTOMÁTICA DE ÁREA
        $usuarioActual = Auth::user();
        
        // Guardamos la persona con el área del usuario actual.
        // Si es admin y no tiene área, se guardará como null (nacional/sin área).
        $validated['area_intervencion_id'] = $usuarioActual->area_intervencion_id; 

        Persona::create($validated);

        return redirect()->route('persona.index')->with('success', 'Persona registrada exitosamente.');
    }

    public function edit($id)
    {
        $usuarioActual = Auth::user();
        $query = Persona::where('id_persona', $id);

        // SEGURIDAD: Verificar área antes de dejar editar
        if ($usuarioActual->rol !== 'admin') {
            $query->where('area_intervencion_id', $usuarioActual->area_intervencion_id);
        }

        $persona = $query->firstOrFail();

        return view('persona.edit', compact('persona'));
    }

    public function update(Request $request, $id)
    {
        $usuarioActual = Auth::user();
        $query = Persona::where('id_persona', $id);
        
        // SEGURIDAD
        if ($usuarioActual->rol !== 'admin') {
            $query->where('area_intervencion_id', $usuarioActual->area_intervencion_id);
        }
        
        $persona = $query->firstOrFail();

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'carnet_identidad' => ['required','string','max:20', 
                Rule::unique('PERSONA', 'carnet_identidad')->ignore($persona->id_persona, 'id_persona')
            ],
            'celular' => 'nullable|string|max:20',
            'procedencia' => 'nullable|string|max:100',
            'genero' => 'required|in:M,F',
        ]);

        $persona->update($validated);

        return redirect()->route('persona.index')->with('success', 'Información actualizada.');
    }

    public function destroy($id)
    {
        $usuarioActual = Auth::user();
        $query = Persona::where('id_persona', $id);
        
        // SEGURIDAD DE ÁREA
        if ($usuarioActual->rol !== 'admin') {
            $query->where('area_intervencion_id', $usuarioActual->area_intervencion_id);
        }
        
        $persona = $query->firstOrFail();

        // 1. VALIDACIONES LÓGICAS
        if ($persona->usuario()->exists()) {
            return redirect()->route('persona.index')->with('error', 'No se puede eliminar: Esta persona es un USUARIO del sistema.');
        }

        if ($persona->beneficiario()->exists()) {
            return redirect()->route('persona.index')->with('error', 'No se puede eliminar: Esta persona es un BENEFICIARIO y tiene fichas asociadas.');
        }
        
        // Nueva validación: ¿Participó en actividades?
        // Asumiendo que tienes un modelo Participante o relación definida
        if (\App\Models\Participante::where('id_persona', $id)->exists()) {
             return redirect()->route('persona.index')->with('error', 'No se puede eliminar: Esta persona ha participado en Actividades/Sesiones.');
        }

        // 2. INTENTO DE ELIMINACIÓN CON CAPTURA DE ERROR
        try {
            $persona->delete();
            return redirect()->route('persona.index')->with('success', 'Persona eliminada correctamente.');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Si la base de datos rechaza la eliminación (Foreign Key Constraint), mostramos el error técnico
            // El código 1451 es "Cannot delete or update a parent row: a foreign key constraint fails"
            if ($e->errorInfo[1] == 1451) {
                return redirect()->route('persona.index')->with('error', 'No se puede eliminar: La persona está vinculada a otros registros en la base de datos (Asistencias, etc).');
            }
            
            return redirect()->route('persona.index')->with('error', 'Error crítico al eliminar: ' . $e->getMessage());
        }
    }

    /**
     * Método de seguridad para evitar BadMethodCallException si las rutas chocan.
     */
    public function show($id)
    {
        // Si intentan ver una persona directamente, redirigimos al editar o al index
        return redirect()->route('persona.edit', $id);
    }

    public function search(Request $request) 
    {
        $query = $request->input('q');

        // ... (Tu lógica de filtrado de $personasQuery) ...

        $personas = $personasQuery->limit(15)->get();

        $results = $personas->map(function ($persona) {
            
            // 🚨 CONSULTA MÁS ROBUSTA: Usamos where en lugar de find()
            // Esto evita problemas de casting o claves no incrementales.
            $participanteRecord = Participante::where('id_persona', $persona->id_persona)->first(); 
            
            $defaultInstId = $participanteRecord ? $participanteRecord->id_institucion : null;
            
            // Si quieres DEPURAR (ver el valor) en esta línea puedes usar:
            // error_log("Persona ID: {$persona->id_persona}, Institución ID: {$defaultInstId}");
            
            return [
                'id' => $persona->id_persona,
                'text' => $persona->nombre . ' ' . $persona->apellido_paterno . ' ' . ($persona->apellido_materno ?? ''),
                'ci' => $persona->carnet_identidad,
                'default_institucion_id' => $defaultInstId, // <-- Dato clave
            ];
        });

        return response()->json(['results' => $results]);
    }


    // En app/Http/Controllers/PersonaController.php

    // ... (al final de la clase, después de la función destroy o show)

    /**
     * Busca una Persona por Carnet de Identidad (CI) con coincidencia EXACTA.
     * CRÍTICO: Aplica el filtro de área para usuarios 'registrador' o 'coordinador'.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchByCiStrict(Request $request)
    {
        // 1. Validación: Asegurar que el CI se envíe y no esté vacío
        $request->validate(['ci' => 'required|string|min:1']);

        $ci = trim($request->input('ci'));
        $usuarioActual = Auth::user();

        // 2. INICIAR CONSULTA CON MATCH EXACTO
        // Usamos '=' para forzar que el C.I. sea idéntico al valor ingresado (¡SOLUCIONA TU PROBLEMA!)
        $query = \App\Models\Persona::where('carnet_identidad', $ci); 

        // 3. SEGURIDAD: FILTRO POR ÁREA (Para rol Registrador/Coordinador)
        // Si NO es Admin, filtra por su área de intervención. (MANTIENE TU REGLA)
        if ($usuarioActual->rol !== 'admin') {
            // Asumiendo que el usuario tiene un campo 'area_intervencion_id'
            $query->where('area_intervencion_id', $usuarioActual->area_intervencion_id);
        }
        
        // Buscar la persona (solo el primer resultado)
        $persona = $query->first();

        if ($persona) {
            // Opcional: Cargar la relación 'participaciones' que a su vez tiene 'institucion'
            // Esto es necesario para ver si la persona ya está afiliada
            $persona->load('participaciones.institucion');
            
            return response()->json([
                'success' => true,
                'persona' => $persona
            ]);
        }

        // 4. No encontrado
        return response()->json([
            'success' => false,
            'message' => 'Persona con C.I. no encontrada. Asegúrese de que el C.I. esté completo y pertenezca a su área.'
        ]);
    }

}

