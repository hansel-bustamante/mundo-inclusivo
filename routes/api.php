<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegistraController;
use App\Http\Controllers\AsistenciaSesionController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\FichaRegistroController;
use App\Http\Controllers\ParticipaEnController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\BeneficiarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CodigoActividadController;
use App\Http\Controllers\AreaIntervencionController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\PersonaController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/





Route::apiResource('registra', RegistraController::class);
Route::apiResource('asistencia-sesion', AsistenciaSesionController::class);
Route::apiResource('sesiones', SesionController::class);
Route::apiResource('evaluaciones', EvaluacionController::class);
Route::apiResource('seguimiento', SeguimientoController::class);
Route::apiResource('ficha_registro', FichaRegistroController::class);
Route::get('participa_en', [ParticipaEnController::class, 'index']);
Route::post('participa_en', [ParticipaEnController::class, 'store']);
Route::get('participa_en/{id_persona}/{id_actividad}', [ParticipaEnController::class, 'show']);
Route::put('participa_en/{id_persona}/{id_actividad}', [ParticipaEnController::class, 'update']);
Route::delete('participa_en/{id_persona}/{id_actividad}', [ParticipaEnController::class, 'destroy']);
Route::apiResource('participa_en', ParticipaEnController::class);
Route::apiResource('actividades', ActividadController::class);
Route::apiResource('participantes', ParticipanteController::class);
Route::apiResource('beneficiarios', BeneficiarioController::class);
Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('codigo_actividad', CodigoActividadController::class);
Route::apiResource('areas_intervencion', AreaIntervencionController::class);
Route::apiResource('personas', PersonaController::class);
Route::apiResource('instituciones', InstitucionController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
