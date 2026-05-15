<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controladores
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\FichaBeneficiarioController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\AreaIntervencionController;
use App\Http\Controllers\CodigoActividadController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\ParticipaEnController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\AsistenciaSesionController;
use App\Http\Controllers\ReporteController; // Agregado para Reportes
use App\Http\Controllers\PasswordController; // Agregado para el cambio de clave
use App\Http\Controllers\AdminDashboardController; // <--- AGREGAR ESTO
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes (SEGURIZADO)
|--------------------------------------------------------------------------
*/

// 1. AUTENTICACIÓN
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirección inicial al dashboard correcto después de login
Route::get('/', function () {
    if (Auth::check()) {
        // 🛑 VERIFICACIÓN DE SEGURIDAD AGREGADA
        if(Auth::user()->must_change_password == 1) {
            return redirect()->route('password.change');
        }
        
        if(Auth::user()->rol == 'admin') return redirect()->route('admin.dashboard');
        if(Auth::user()->rol == 'registrador') return redirect()->route('registrador.dashboard');
        if(Auth::user()->rol == 'coordinador') return redirect()->route('coordinador.dashboard');
    }
    return redirect()->route('login');
});

// ========================================================================
// 2. RUTAS DE API INTERNAS (Prioridad Alta)
// ========================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/ajax/buscador/personas', [FichaBeneficiarioController::class, 'searchPersonas'])
          ->name('api.personas.search');

    // 2. NUEVA API EXCLUSIVA PARA ACTIVIDADES/PARTICIPANTES
    Route::get('/ajax/actividad/buscar-participantes', [ActividadController::class, 'searchParticipantes'])
         ->name('api.actividad.participantes.search');
         
    // -----------------------------------------------------------
    // NUEVA RUTA: Búsqueda estricta por C.I. (Participantes)
    // -----------------------------------------------------------
    Route::get('/api/persona/search_ci_strict', [PersonaController::class, 'searchByCiStrict'])
          ->name('persona.search_ci_strict');
    
    // APIs Internas (Asistencia)
    Route::prefix('api/asistencia-sesion')->group(function () {
        Route::post('/', [AsistenciaSesionController::class, 'store'])->name('api.asistencia.store');
        Route::delete('{id_sesion}/{id_persona}', [AsistenciaSesionController::class, 'destroy'])->name('api.asistencia.destroy');
    });
});

// ========================================================================
// 3. RUTAS COMUNES (ACCESIBLES PARA TODOS LOS LOGUEADOS)
// ========================================================================
Route::middleware(['auth'])->group(function () {

    // --- RUTAS PARA CAMBIO DE CONTRASEÑA (AGREGADAS) ---
    Route::get('/cambiar-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/cambiar-password', [PasswordController::class, 'updatePassword'])->name('password.update');

    // --- RUTAS DE REPORTES (AGREGADAS) ---
    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('reportes.index');
        
        // Rutas específicas que faltaban
        Route::get('/personas', [ReporteController::class, 'personas'])->name('reportes.personas');
        Route::get('/beneficiarios', [ReporteController::class, 'beneficiarios'])->name('reportes.beneficiarios');
        Route::get('/seguimiento', [ReporteController::class, 'seguimiento'])->name('reportes.seguimiento');
        Route::get('/evaluaciones', [ReporteController::class, 'evaluaciones'])->name('reportes.evaluaciones');
        Route::get('/sesiones', [ReporteController::class, 'sesiones'])->name('reportes.sesiones');
        Route::get('/asistencia', [ReporteController::class, 'asistencia'])->name('reportes.asistencia');
        
        Route::get('/generar/{tipo}', [ReporteController::class, 'generar'])->name('reporte.generar');
    });

    // APIs Internas (Participación)
    Route::prefix('participaen')->group(function () {
        Route::get('/', [ParticipaEnController::class, 'index'])->name('participaen.index');
        Route::post('/', [ParticipaEnController::class, 'store'])->name('participaen.store');
        Route::get('{id_persona}/{id_actividad}', [ParticipaEnController::class, 'show'])->name('participaen.show');
        Route::put('{id_persona}/{id_actividad}', [ParticipaEnController::class, 'update'])->name('participaen.update');
        Route::delete('{id_persona}/{id_actividad}', [ParticipaEnController::class, 'destroy'])->name('participaen.destroy');
    });
    
    // Instituciones (Lo ven Admin, Registrador y Coordinador)
    // AQUI AGREGAMOS 'force.change' PARA BLOQUEAR SI NO CAMBIA LA CLAVE
    Route::middleware(['role:admin,registrador,coordinador', 'force.change'])->group(function () {
        Route::resource('institucion', InstitucionController::class);
    });
});

// ========================================================================
// 4. GESTIÓN OPERATIVA (SOLO ADMIN y REGISTRADOR)
// Incluye: Personas, Beneficiarios, Actividades, Sesiones
// ========================================================================
// 🔒 AGREGADO 'force.change' AQUÍ
Route::middleware(['auth', 'role:admin,registrador', 'force.change'])->group(function () {
    
    // Personas
    Route::resource('persona', PersonaController::class);

    // Ficha Unificada
    Route::resource('ficha-beneficiario', FichaBeneficiarioController::class)
             ->names('ficha_beneficiario');

    // Actividades
    Route::resource('actividad', ActividadController::class);
    Route::get('actividad/{actividad}/participantes', [ActividadController::class, 'editParticipantes'])->name('actividad.participantes.edit');
    Route::post('actividad/{id}/add-participante', [ActividadController::class, 'addParticipante'])->name('actividad.addParticipante');

    // ========================================================================
    // RUTAS DE SESIÓN (PERSONALIZADAS Y RESOURCE)
    // ========================================================================
    Route::prefix('sesion')->name('sesion.')->group(function () {
        
        // 1. GESTIÓN DE ASISTENCIA
        Route::get('{sesion}/asistencia/edit', [SesionController::class, 'editAsistencia'])->name('asistencia.edit');
        
        // 2. Ver sesiones de una actividad específica
        Route::get('actividad/{actividad}', [SesionController::class, 'indexPorActividad'])
              ->name('por_actividad');

        // 3. Crear sesión para una actividad específica
        Route::get('actividad/{actividad}/crear', [SesionController::class, 'createPorActividad'])
              ->name('create_por_actividad');

        // 4. RESOURCE
        Route::resource('/', SesionController::class)->parameters(['' => 'sesion']);
    });
});

// ========================================================================
// 5. MONITOREO (SOLO ADMIN y COORDINADOR)
// Incluye: Seguimiento, Evaluaciones
// ========================================================================
// 🔒 AGREGADO 'force.change' AQUÍ
Route::middleware(['auth', 'role:admin,coordinador', 'force.change'])->group(function () {
    
    Route::resource('evaluacion', EvaluacionController::class);
    Route::resource('seguimiento', SeguimientoController::class);
    
});

// ========================================================================
// 6. ADMINISTRACIÓN PURA (SOLO ADMIN)
// Incluye: Usuarios, Áreas, Códigos
// ========================================================================
// 🔒 AGREGADO 'force.change' AQUÍ
Route::middleware(['auth', 'role:admin', 'force.change'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Ruta para que el admin resetee la clave de un usuario
    Route::post('/usuario/{id}/reset-password', [UsuarioController::class, 'adminResetPassword'])->name('usuario.reset_password');
    
    // Gestión de Usuarios
    Route::get('usuario/create/{persona}', [UsuarioController::class, 'create'])->name('usuario.create_for_persona');
    Route::resource('usuario', UsuarioController::class);

    // Catálogos de Configuración
    Route::resource('area', AreaIntervencionController::class);
    Route::resource('codigo', CodigoActividadController::class);
});


// ========================================================================
// 7. DASHBOARDS ESPECÍFICOS
// ========================================================================
// 🔒 AGREGADO 'force.change' AQUÍ PARA PROTEGER EL DASHBOARD
// 1. GRUPO REGISTRADOR
Route::middleware(['auth', 'role:registrador', 'force.change'])->prefix('registrador')->group(function () {
    
    // Nota: Si usas prefix('registrador'), la ruta get debería ser solo '/dashboard' 
    // para que la URL quede: /registrador/dashboard
    // Si pones get('/registrador/dashboard'), la URL final será /registrador/registrador/dashboard
    
    Route::get('/dashboard', [App\Http\Controllers\RegistradorDashboardController::class, 'index'])
        ->name('registrador.dashboard');
});

// 2. GRUPO COORDINADOR (CORREGIDO)
Route::middleware(['auth', 'role:coordinador', 'force.change'])->prefix('coordinador')->group(function () {
    
    // 🛑 ANTES (MALO para Dashboard dinámico):
    // Route::get('/dashboard', function () { return view('coordinador.dashboard'); })->name('coordinador.dashboard');

    // ✅ AHORA (CORRECTO): Apuntando al Controlador que calcula los datos
    Route::get('/dashboard', [App\Http\Controllers\CoordinadorDashboardController::class, 'index'])
        ->name('coordinador.dashboard');
        
});