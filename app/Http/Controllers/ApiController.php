<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validar datos entrantes
        $credentials = $request->validate([
            'nombre_usuario' => 'required',
            'contrasena' => 'required'
        ]);

        // 2. Intentar loguear manualmente
        if (Auth::attempt(['nombre_usuario' => $request->nombre_usuario, 'password' => $request->contrasena])) {
            
            $user = Auth::user();
            
            // Crear Token
            $token = $user->createToken('AndroidApp')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Bienvenido',
                'token' => $token,
                'usuario' => $user->nombre_usuario,
                'area_id' => $user->area_intervencion_id,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Credenciales incorrectas'
        ], 401);
    }

    public function download(Request $request)
    {
        $user = Auth::user();
        $area = $user->area_intervencion_id;

        $filtroArea = function ($query) use ($area) {
            if ($area === 'M6') {
                $query->where('area_intervencion_id', 'LIKE', 'M6%');
            } else {
                $query->where('area_intervencion_id', $area);
            }
        };

        // 1. Datos principales (Filtrados por área)
        $actividades = \App\Models\Actividad::where($filtroArea)->get();
        $personas = \App\Models\Persona::where($filtroArea)->get();
        $codigos = \App\Models\CodigoActividad::all();
        $instituciones = \App\Models\Institucion::all();

        // 2. LISTA GLOBAL DE C.I. (Para validar duplicados offline)
        // Obtenemos SOLO la columna carnet_identidad de TODA la tabla
        $globalCis = \App\Models\Persona::pluck('carnet_identidad');

        // 3. Sesiones (De las actividades descargadas)
        $actividadesIds = $actividades->pluck('id_actividad');
        $sesiones = \App\Models\Sesion::whereIn('id_actividad', $actividadesIds)->get();

        // 4. Vinculaciones (Participa_en)
        $participaEn = \Illuminate\Support\Facades\DB::table('participa_en')
                        ->whereIn('id_actividad', $actividadesIds)
                        ->get();
        
        // 5. Asistencias (De las sesiones descargadas)
        $sesionesIds = $sesiones->pluck('id_sesion');
        $asistencias = \Illuminate\Support\Facades\DB::table('asistencia_sesion')
                        ->whereIn('id_sesion', $sesionesIds)
                        ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'actividades' => $actividades,
                'personas' => $personas,
                'codigos' => $codigos,
                'instituciones' => $instituciones,
                'sesiones' => $sesiones,
                'participa_en' => $participaEn,
                'asistencias' => $asistencias, // <--- AQUÍ FALTABA LA COMA
                'global_cis' => $globalCis
            ]
        ]);
    }

    public function upload(Request $request)
    {
        $input = $request->all();
        
        $mapPersonas = []; // Map: ID Local Celular => ID Real Server
        $mapActividades = [];
        $mapSesiones = []; // Map: ID Local Celular => ID Real Server

        \Illuminate\Support\Facades\DB::transaction(function () use ($input, &$mapPersonas, &$mapSesiones) {
            
            // 1. PERSONAS NUEVAS
            if (!empty($input['personas'])) {
                foreach ($input['personas'] as $p) {
                    $nueva = \App\Models\Persona::create([
                        'nombre' => $p['nombre'],
                        'apellido_paterno' => $p['paterno'],
                        'apellido_materno' => $p['materno'] ?? null,
                        'carnet_identidad' => $p['ci'],
                        'fecha_nacimiento' => $p['fecha_nacimiento'],
                        'genero' => $p['genero'],
                        'celular' => $p['celular'] ?? null,
                        'procedencia' => $p['procedencia'] ?? null,
                        'area_intervencion_id' => $p['area_intervencion_id']
                    ]);
                    $mapPersonas[$p['id_local']] = $nueva->id_persona;
                }
            }

            // 2. ACTIVIDADES (Bloque NUEVO)
            if (!empty($input['actividades'])) {
                foreach ($input['actividades'] as $act) {
                    $nuevaActividad = \App\Models\Actividad::create([
                        'nombre' => $act['nombre'],
                        'fecha' => $act['fecha'],
                        'lugar' => $act['lugar'],
                        'descripcion' => $act['descripcion'] ?? null,
                        'codigo_actividad_id' => $act['codigo_actividad_id'], // A1, B2...
                        'area_intervencion_id' => $act['area_intervencion_id']
                    ]);
                    
                    // Guardamos: ID Local (del celular) => ID Real (MySQL)
                    $mapActividades[$act['id_local']] = $nuevaActividad->id_actividad;
                }
            }

            // 2. SESIONES NUEVAS
// 3. SESIONES (CORREGIDO: CÁLCULO AUTOMÁTICO DE NRO_SESION)
            if (!empty($input['sesiones'])) {
                foreach ($input['sesiones'] as $s) {
                    
                    // A. Resolver ID de Actividad Real
                    $idActividadReal = $s['id_actividad'];
                    
                    // Si la actividad fue creada en este mismo upload, usamos el mapeo
                    if (isset($mapActividades[$s['id_actividad']])) {
                        $idActividadReal = $mapActividades[$s['id_actividad']];
                    }

                    // B. CALCULAR EL NÚMERO DE SESIÓN SECUENCIAL
                    // Buscamos el nro_sesion más alto que existe ACTUALMENTE en la base de datos para esa actividad
                    $maxNumero = \App\Models\Sesion::where('id_actividad', $idActividadReal)->max('nro_sesion');
                    $siguienteNumero = intval($maxNumero) + 1;

                    // C. Ajustar el Tema si es necesario (Opcional)
                    // Si el celular mandó "Sesión N° 0" o algo genérico, podemos actualizarlo aquí.
                    // Por ahora usamos el tema que mandó el celular, o generamos uno si viene vacío.
                    $tema = $s['tema'];
                    if (empty($tema) || str_contains($tema, 'Sesión N°')) {
                         // Forzamos el nombre correcto para que coincida con el número real
                        $tema = 'Sesión N° ' . $siguienteNumero;
                    }

                    // D. Crear la Sesión
                    $nuevaSesion = \App\Models\Sesion::create([
                        'id_actividad' => $idActividadReal,
                        'tema' => $tema,
                        'fecha' => $s['fecha'],
                        'hora_inicio' => '08:00', // Default o recibir del JSON si lo agregas en Android
                        'hora_fin' => '12:00',
                        'nro_sesion' => $siguienteNumero // <--- AQUÍ GUARDAMOS EL 1, 2, 3...
                    ]);
                    
                    // Mapeo para devolver al celular
                    $mapSesiones[$s['id_local']] = $nuevaSesion->id_sesion;
                }
            }

            // 3. VINCULACIONES (OPTIMIZADO)
            if (!empty($input['participa_en'])) {
                foreach ($input['participa_en'] as $pe) {
                    // A. Traducir ID Persona
                    $idP = isset($mapPersonas[$pe['id_persona']]) ? $mapPersonas[$pe['id_persona']] : $pe['id_persona'];
                    
                    if (!$idP) continue; // Seguridad: Si no hay ID válido, saltamos

                    // B. Guardar en 'participa_en'
                    // Preparamos los datos a guardar
                    $datosGuardar = [
                        'tiene_discapacidad' => $pe['tiene_discapacidad'] ?? 0,
                        'es_familiar' => $pe['es_familiar'] ?? 0,
                        'firma' => $pe['firma'] ?? 0,
                        'updated_at' => now(),
                    ];

                    // Verificamos si existe para decidir si ponemos created_at
                    $existe = \Illuminate\Support\Facades\DB::table('participa_en')
                        ->where('id_persona', $idP)
                        ->where('id_actividad', $pe['id_actividad'])
                        ->exists();

                    if (!$existe) {
                        $datosGuardar['created_at'] = now(); // Solo si es nuevo
                    }

                    \Illuminate\Support\Facades\DB::table('participa_en')->updateOrInsert(
                        [
                            'id_persona' => $idP,
                            'id_actividad' => $pe['id_actividad']
                        ],
                        $datosGuardar
                    );

                    // C. Guardar Institución (Tabla 'participante')
                    if (isset($pe['id_institucion']) && $pe['id_institucion'] != null && $pe['id_institucion'] > 0) {
                        // Verificamos existencia para timestamps
                        $existePart = \Illuminate\Support\Facades\DB::table('participante')
                            ->where('id_persona', $idP)
                            ->exists();
                        
                        $datosPart = [
                            'id_institucion' => $pe['id_institucion'],
                            'updated_at' => now()
                        ];
                        
                        if (!$existePart) {
                            $datosPart['created_at'] = now();
                        }

                        \Illuminate\Support\Facades\DB::table('participante')->updateOrInsert(
                            ['id_persona' => $idP],
                            $datosPart
                        );
                    }
                }
            }


            // 4. ASISTENCIAS
            if (!empty($input['asistencias'])) {
                foreach ($input['asistencias'] as $asis) {
                    // A. Traducir ID Persona
                    $idP = isset($mapPersonas[$asis['id_persona']]) ? $mapPersonas[$asis['id_persona']] : $asis['id_persona'];
                    
                    // B. Traducir ID Sesión (Si la sesión fue creada en el celular)
                    $idS = isset($mapSesiones[$asis['id_sesion']]) ? $mapSesiones[$asis['id_sesion']] : $asis['id_sesion'];

                    // C. Guardar explícitamente usando DB (Más robusto para claves compuestas)
                    \Illuminate\Support\Facades\DB::table('asistencia_sesion')->updateOrInsert(
                        [
                            'id_sesion' => $idS,
                            'id_persona' => $idP
                        ],
                        [
                            'firma' => (int)$asis['firma'],
                            'observaciones' => 'Móvil'
                        ]
                    );
                }
            }
        });

        return response()->json([
            'status' => true,
            'id_mapping' => $mapPersonas,
            'id_mapping_sesiones' => $mapSesiones,
            'id_mapping_actividades' => $mapActividades
        ]);
    }
}