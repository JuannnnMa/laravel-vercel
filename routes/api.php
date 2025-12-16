<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\CalificacionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
// Rutas para Estudiantes
Route::prefix('estudiantes')->group(function () {
    Route::get('/', [EstudianteController::class, 'index']);
    Route::post('/', [EstudianteController::class, 'store']);
    Route::get('/create', [EstudianteController::class, 'create']);
    Route::get('/edit/{id}', [EstudianteController::class, 'edit']);
    Route::get('/{id}', [EstudianteController::class, 'show']);
    Route::put('/{id}', [EstudianteController::class, 'update']);
    Route::post('/{id}/matricular', [EstudianteController::class, 'matricular']);
});

// Rutas para Docentes
Route::prefix('docentes')->group(function () {
    Route::get('/', [DocenteController::class, 'index']);
    Route::post('/', [DocenteController::class, 'store']);
    Route::get('/{id}', [DocenteController::class, 'show']);
    Route::put('/{id}', [DocenteController::class, 'update']);
    Route::post('/{id}/asignar-materia', [DocenteController::class, 'asignarMateria']);
});

// Rutas para Calificaciones
Route::prefix('calificaciones')->group(function () {
    Route::get('/', [CalificacionController::class, 'index']);
    Route::post('/', [CalificacionController::class, 'store']);
    Route::get('/promedio/{idMatricula}/{idMateria}/{idPeriodo}', [CalificacionController::class, 'promedioEstudiante']);
});
*/

// Rutas adicionales para consultas específicas
Route::get('/grados', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\Grado::with('secciones')->activo()->ordenado()->get(),
        'message' => 'Grados obtenidos correctamente'
    ]);
});

Route::get('/materias', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\Materia::activo()->orderBy('nombre')->get(),
        'message' => 'Materias obtenidas correctamente'
    ]);
});

Route::get('/anios-academicos', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\AnioAcademico::with('periodos')->orderBy('anio', 'desc')->get(),
        'message' => 'Años académicos obtenidos correctamente'
    ]);
});
// Rutas para App Móvil (Tutores)
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    
    Route::post('/fcm-token', [\App\Http\Controllers\Api\AuthController::class, 'updateFcmToken']);

    Route::prefix('tutor')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Api\TutorController::class, 'dashboard']);
        Route::get('/hijos/{id}/notas', [\App\Http\Controllers\Api\TutorController::class, 'notas']);
        Route::get('/hijos/{id}/asistencia', [\App\Http\Controllers\Api\TutorController::class, 'asistencia']);
        Route::get('/comunicados', [\App\Http\Controllers\Api\TutorController::class, 'comunicados']);
        Route::post('/mark-seen', [\App\Http\Controllers\Api\TutorController::class, 'markAsSeen']);
    });
});
