<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Aseguramos que Auth esté disponible
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\Auth\LoginController;

// Usamos los controladores de catálogos
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\AreaIntervencionController;
use App\Http\Controllers\CodigoActividadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar rutas web para tu aplicación. Estas rutas se cargan
| por el RouteServiceProvider y se asignan al grupo de middleware "web".
|
*/

// Catálogo de Personas (ejemplo de recurso)
Route::resource('personas', PersonaController::class);

// --- RUTAS DE AUTENTICACIÓN ---
// Muestra el formulario de login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Procesa el intento de login
Route::post('/login', [LoginController::class, 'login']);
// Cierra la sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- RUTA RAIZ (Index / Home) CORREGIDA ---
// Esta ruta es única y maneja la redirección después del login.
Route::get('/', function () {
    // Si estás logueado, redirige a tu dashboard de administrador (por defecto),
    // si no, muestra la vista 'welcome'.
    if (Auth::check()) {
        // Redirigimos directamente al dashboard del rol principal.
        // Asumiendo que 'admin' es el rol más común o el principal a donde ir.
        // Si tienes múltiples roles, podrías usar una lógica más compleja aquí
        // (como un método 'home' en un controlador que revise el rol).
        return redirect()->route('admin.dashboard');
    }
    return view('welcome');
})->name('home');


// --- RUTAS PROTEGIDAS DEL ADMINISTRADOR ---
// Usamos el middleware 'auth' para verificar login, y 'admin' para verificar el rol.
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // DASHBOARD PRINCIPAL DEL ADMIN
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('admin.dashboard');

    // ** FASE 2: RUTAS CRUD PARA CATÁLOGOS **

    // 1. INSTITUCIONES
    Route::resource('institucion', InstitucionController::class);

    // 2. ÁREAS DE INTERVENCIÓN
    Route::resource('area', AreaIntervencionController::class);

    // 3. CÓDIGOS DE ACTIVIDAD
    Route::resource('codigo', CodigoActividadController::class);

    // 4. ACTIVIDADES (FASE PRINCIPAL)
    Route::resource('actividad', \App\Http\Controllers\ActividadController::class); // <-- AÑADE ESTO

});


// --- RUTAS PARA OTROS ROLES (Solo placeholders) ---

// Redireccionan a sus dashboards específicos si el middleware de rol lo permite
Route::middleware(['auth', 'coordinador'])->prefix('coordinador')->group(function () {
    Route::get('/dashboard', function () { return view('coordinador.dashboard'); })->name('coordinador.dashboard');
});

Route::middleware(['auth', 'registrador'])->prefix('registrador')->group(function () {
    Route::get('/dashboard', function () { return view('registrador.dashboard'); })->name('registrador.dashboard');
});
