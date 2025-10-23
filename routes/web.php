<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controladores principales
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\BeneficiarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\ParticipaEnController; 
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\FichaRegistroController; 
// Controladores de catálogos
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\AreaIntervencionController;
use App\Http\Controllers\CodigoActividadController;
// Controladores de API
use App\Http\Controllers\AsistenciaSesionController; 
// Controladores de autenticación
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rutas web para la aplicación Mundo Inclusivo.
|
*/

// --- RUTAS DE AUTENTICACIÓN ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- RUTA RAÍZ (Index / Home) ---
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('welcome');
})->name('home');


// --- GRUPO DE RUTAS PROTEGIDAS DEL ADMINISTRADOR ---
// Middleware: 'auth' (usuario logueado) y 'admin' (rol de administrador)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // DASHBOARD PRINCIPAL DEL ADMIN
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('admin.dashboard');

    // ** RUTAS CRUD PARA CATÁLOGOS **
    // 1. INSTITUCIONES
    Route::resource('institucion', InstitucionController::class);
    // 2. ÁREAS DE INTERVENCIÓN
    Route::resource('area', AreaIntervencionController::class);
    // 3. CÓDIGOS DE ACTIVIDAD
    Route::resource('codigo', CodigoActividadController::class);

    // ** RUTAS CRUD PARA ENTIDADES PRINCIPALES **
    
    // 4. PERSONAS
    Route::resource('persona', PersonaController::class);
    // 5. BENEFICIARIOS (Usando id_persona como clave)
    Route::resource('beneficiario', BeneficiarioController::class)->parameters([
        'beneficiario' => 'id_persona' 
    ]);
    
    // 6. FICHA DE REGISTRO <-- CORRECCIÓN DE NOMBRE AQUÍ
    Route::resource('ficha-registro', FichaRegistroController::class)->names('ficha_registro');

    // 7. USUARIOS (Usando id_persona como clave)
    Route::resource('usuario', UsuarioController::class)->parameters([
        'usuario' => 'id_persona' 
    ]);
    // 8. ACTIVIDADES
    Route::resource('actividad', ActividadController::class);

    // RUTA EXTRA: Formulario de gestión de participantes de una actividad (Web/Blade)
    Route::get('actividad/{actividad}/participantes', [ActividadController::class, 'editParticipantes'])
        ->name('actividad.participantes.edit');

    // 9. SESIONES
    Route::resource('sesion', SesionController::class);
    
    // RUTA AÑADIDA: Enlace para el formulario de gestión de asistencia a sesión (Web)
    Route::get('sesion/{sesion}/asistencia', [SesionController::class, 'editAsistencia'])
        ->name('sesion.asistencia.edit');
        
    // 10. EVALUACIONES
    Route::resource('evaluacion', EvaluacionController::class);

    // 11. SEGUIMIENTO 
    Route::resource('seguimiento', SeguimientoController::class)->names('seguimiento');

    // 12. PARTICIPA_EN (Relación Persona - Actividad) - Rutas de API para la tabla pivote
    Route::prefix('participaen')->group(function () {
        Route::get('/', [ParticipaEnController::class, 'index'])->name('participaen.index');
        Route::post('/', [ParticipaEnController::class, 'store'])->name('participaen.store');
        Route::get('{id_persona}/{id_actividad}', [ParticipaEnController::class, 'show'])->name('participaen.show');
        Route::put('{id_persona}/{id_actividad}', [ParticipaEnController::class, 'update'])->name('participaen.update');
        Route::delete('{id_persona}/{id_actividad}', [ParticipaEnController::class, 'destroy'])->name('participaen.destroy');
    });
});


// --- RUTAS DE API PARA GESTIÓN DE ASISTENCIA ---
Route::middleware(['auth'])->prefix('api/asistencia-sesion')->group(function () {
    Route::post('/', [AsistenciaSesionController::class, 'store'])->name('api.asistencia.store');
    Route::delete('{id_sesion}/{id_persona}', [AsistenciaSesionController::class, 'destroy'])
        ->name('api.asistencia.destroy');
});


// --- RUTAS PARA OTROS ROLES (Placeholders) ---

Route::middleware(['auth', 'coordinador'])->prefix('coordinador')->group(function () {
    Route::get('/dashboard', function () { return view('coordinador.dashboard'); })->name('coordinador.dashboard');
});

Route::middleware(['auth', 'registrador'])->prefix('registrador')->group(function () {
    Route::get('/dashboard', function () { return view('registrador.dashboard'); })->name('registrador.dashboard');
});