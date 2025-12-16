<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProfesorController;
use App\Http\Controllers\Admin\EstudianteController;
use App\Http\Controllers\Admin\TutorController;
use App\Http\Controllers\Admin\MateriaController;
use App\Http\Controllers\Admin\CursoController;
use App\Http\Controllers\Admin\ParaleloController;
use App\Http\Controllers\Admin\HorarioController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/cambiar-gestion', [AdminDashboardController::class, 'cambiarGestion'])->name('admin.cambiar-gestion');
    
    // Usuarios
    Route::get('/usuarios', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.usuarios');
    Route::post('/usuarios/{id}/password', [App\Http\Controllers\Admin\UserController::class, 'updatePassword'])->name('admin.usuarios.password');

    // Profesores
    Route::get('/profesores', [ProfesorController::class, 'index'])->name('admin.profesores');
    Route::get('/profesores/create', [ProfesorController::class, 'create'])->name('admin.profesores.create');
    Route::post('/profesores', [ProfesorController::class, 'store'])->name('admin.profesores.store');
    Route::get('/profesores/{id}/edit', [ProfesorController::class, 'edit'])->name('admin.profesores.edit');
    Route::put('/profesores/{id}', [ProfesorController::class, 'update'])->name('admin.profesores.update');
    Route::delete('/profesores/{id}', [ProfesorController::class, 'destroy'])->name('admin.profesores.destroy');
    Route::post('/profesores/{id}/activate', [ProfesorController::class, 'activate'])->name('admin.profesores.activate');
    Route::get('/profesores/{id}/materias', [ProfesorController::class, 'getMaterias'])->name('admin.profesores.materias');
    Route::post('/profesores/materias/remove', [ProfesorController::class, 'removeMateria'])->name('admin.profesores.materias.remove');
    Route::post('/profesores/materias/add', [ProfesorController::class, 'addMateria'])->name('admin.profesores.materias.add');
    
    // Estudiantes
    Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('admin.estudiantes');
    Route::get('/estudiantes/create', [EstudianteController::class, 'create'])->name('admin.estudiantes.create');
    Route::post('/estudiantes', [EstudianteController::class, 'store'])->name('admin.estudiantes.store');
    Route::get('/estudiantes/{id}/edit', [EstudianteController::class, 'edit'])->name('admin.estudiantes.edit');
    Route::put('/estudiantes/{id}', [EstudianteController::class, 'update'])->name('admin.estudiantes.update');
    Route::delete('/estudiantes/{id}', [EstudianteController::class, 'destroy'])->name('admin.estudiantes.destroy');
    Route::post('/estudiantes/{id}/activate', [EstudianteController::class, 'activate'])->name('admin.estudiantes.activate');
    Route::get('/estudiantes/{id}/inscripcion', [EstudianteController::class, 'getInscripcionData'])->name('admin.estudiantes.inscripcion');
    Route::post('/estudiantes/inscribir', [EstudianteController::class, 'inscribir'])->name('admin.estudiantes.inscribir');
    Route::get('/estudiantes/{id}/notas', [EstudianteController::class, 'getNotas'])->name('admin.estudiantes.notas');
    Route::get('/estudiantes/{id}/boletin', [EstudianteController::class, 'descargarBoletin'])->name('admin.estudiantes.boletin');
     Route::get('/estudiantes/reporte/asistencia-general', [EstudianteController::class, 'descargarAsistenciaGeneral'])->name('admin.estudiantes.reporte.asistencia-general');
    Route::get('/estudiantes/reporte/notas-general', [EstudianteController::class, 'descargarNotasGeneral'])->name('admin.estudiantes.reporte.notas-general');
    Route::get('/estudiantes/{id}/reporte/asistencia', [EstudianteController::class, 'descargarAsistenciaIndividual'])->name('admin.estudiantes.reporte.asistencia-individual');
    Route::get('/estudiantes/{id}/asistencia', [EstudianteController::class, 'getAsistencia'])->name('admin.estudiantes.asistencia');
    Route::get('/secciones/{id}/capacidad', [EstudianteController::class, 'getSeccionCapacidad'])->name('admin.secciones.capacidad');
    
    // Seguimiento (Kardex)
    Route::get('/seguimientos/{estudiante_id}', [App\Http\Controllers\Admin\SeguimientoController::class, 'index'])->name('admin.seguimientos.index');
    Route::post('/seguimientos/{estudiante_id}', [App\Http\Controllers\Admin\SeguimientoController::class, 'store'])->name('admin.seguimientos.store');
    Route::put('/seguimientos/{id}', [App\Http\Controllers\Admin\SeguimientoController::class, 'update'])->name('admin.seguimientos.update');
    Route::delete('/seguimientos/{id}', [App\Http\Controllers\Admin\SeguimientoController::class, 'destroy'])->name('admin.seguimientos.destroy');

    // Tutores de Estudiantes
    Route::get('/estudiantes/{estudiante}/tutores', [EstudianteController::class, 'tutores'])->name('admin.estudiantes.tutores');
    Route::post('/estudiantes/{estudiante}/tutores', [EstudianteController::class, 'attachTutor'])->name('admin.estudiantes.attach-tutor');
    Route::delete('/parentescos/{parentesco}', [EstudianteController::class, 'detachTutor'])->name('admin.parentescos.destroy');

    // Tutores
    Route::get('/tutores', [TutorController::class, 'index'])->name('admin.tutores');
    Route::get('/tutores/create', [TutorController::class, 'create'])->name('admin.tutores.create');
    Route::post('/tutores', [TutorController::class, 'store'])->name('admin.tutores.store');
    Route::get('/tutores/{id}/edit', [TutorController::class, 'edit'])->name('admin.tutores.edit');
    Route::put('/tutores/{id}', [TutorController::class, 'update'])->name('admin.tutores.update');
    Route::delete('/tutores/{id}', [TutorController::class, 'destroy'])->name('admin.tutores.destroy');
    Route::get('/tutores/{id}/estudiantes', [TutorController::class, 'getEstudiantes'])->name('admin.tutores.estudiantes');
    
    // Materias
    Route::get('/materias', [MateriaController::class, 'index'])->name('admin.materias');
    Route::post('/materias', [MateriaController::class, 'store'])->name('admin.materias.store');
    Route::get('/materias/{id}/edit', [MateriaController::class, 'edit'])->name('admin.materias.edit');
    Route::get('/materias/create', [MateriaController::class, 'create'])->name('admin.materias.create');
    Route::get('/materias/{id}/secciones', [MateriaController::class, 'getSecciones'])->name('admin.materias.secciones');
    
    Route::put('/materias/{id}', [MateriaController::class, 'update'])->name('admin.materias.update');
    Route::delete('/materias/{id}', [MateriaController::class, 'destroy'])->name('admin.materias.destroy');
    Route::post('/materias/{id}/activate', [MateriaController::class, 'activate'])->name('admin.materias.activate');
    
    // Cursos (Grados)
    Route::get('/cursos', [CursoController::class, 'index'])->name('admin.cursos');
    Route::get('/cursos/create', [CursoController::class, 'create'])->name('admin.cursos.create');
    Route::post('/cursos', [CursoController::class, 'store'])->name('admin.cursos.store');
    Route::put('/cursos/{id}', [CursoController::class, 'update'])->name('admin.cursos.update');
    Route::delete('/cursos/{id}', [CursoController::class, 'destroy'])->name('admin.cursos.destroy');
    Route::get('/cursos/{id}/edit', [CursoController::class, 'edit'])->name('admin.cursos.edit');

    // Paralelos (Secciones)
    Route::get('/paralelos', [ParaleloController::class, 'index'])->name('admin.paralelos');
    Route::get('/paralelos/create', [ParaleloController::class, 'create'])->name('admin.paralelos.create');
    Route::post('/paralelos', [ParaleloController::class, 'store'])->name('admin.paralelos.store');
    Route::get('/paralelos/{id}/edit', [ParaleloController::class, 'edit'])->name('admin.paralelos.edit');
    Route::put('/paralelos/{id}', [ParaleloController::class, 'update'])->name('admin.paralelos.update');
    Route::delete('/paralelos/{id}', [ParaleloController::class, 'destroy'])->name('admin.paralelos.destroy');
    
    // Horarios
    Route::get('/horarios', [HorarioController::class, 'index'])->name('admin.horarios');
    Route::get('/horarios/create', [HorarioController::class, 'create'])->name('admin.horarios.create');
    Route::post('/horarios', [HorarioController::class, 'store'])->name('admin.horarios.store');
    Route::get('/horarios/{id}/edit', [HorarioController::class, 'edit'])->name('admin.horarios.edit');
    Route::put('/horarios/{id}', [HorarioController::class, 'update'])->name('admin.horarios.update');
    Route::delete('/horarios/{id}', [HorarioController::class, 'destroy'])->name('admin.horarios.destroy');
    Route::get('/horarios/descargar', [HorarioController::class, 'descargar'])->name('admin.horarios.descargar');
});

// Rutas para Docentes
use App\Http\Controllers\Docente\DocenteAuthController;
use App\Http\Controllers\Docente\DocenteDashboardController;
use App\Http\Controllers\Docente\DocenteNotaController;
use App\Http\Controllers\Docente\DocenteAsistenciaController;

Route::middleware(['auth', 'role:profesor'])->prefix('profesor')->group(function () {
    Route::get('/dashboard', [DocenteDashboardController::class, 'index'])->name('docente.dashboard');
    
    // Notas
    Route::get('/notas', [DocenteNotaController::class, 'index'])->name('docente.notas.index');
    Route::get('/notas/{asignacion}', [DocenteNotaController::class, 'show'])->name('docente.notas.show');
    Route::get('/notas/{asignacion}/estudiante/{estudiante}', [DocenteNotaController::class, 'registrar'])->name('docente.notas.registrar');
    Route::post('/notas/{asignacion}/estudiante/{estudiante}', [DocenteNotaController::class, 'store'])->name('docente.notas.store');
    Route::get('/notas/{asignacion}/estudiante/{estudiante}/descargar', [DocenteNotaController::class, 'descargar'])->name('docente.notas.descargar');
    
    // Asistencia
    Route::get('/asistencia', [DocenteAsistenciaController::class, 'index'])->name('docente.asistencia.index');
    Route::get('/asistencia/{asignacion}', [DocenteAsistenciaController::class, 'show'])->name('docente.asistencia.show');
    Route::post('/asistencia/{asignacion}', [DocenteAsistenciaController::class, 'store'])->name('docente.asistencia.store');
    
    // Subir datos (Notificación)
    Route::post('/upload-data', [DocenteAsistenciaController::class, 'uploadData'])->name('docente.upload.data');

    // Asesoría / Tutoría
    Route::get('/asesoria', [App\Http\Controllers\Docente\AsesoriaController::class, 'index'])->name('docente.asesoria.index');
    Route::get('/asesoria/{id}', [App\Http\Controllers\Docente\AsesoriaController::class, 'show'])->name('docente.asesoria.show');

    // Comunicados Docente
    Route::get('/comunicados', [App\Http\Controllers\Docente\ComunicadoController::class, 'index'])->name('docente.comunicados.index');
    Route::get('/comunicados/{id}', [App\Http\Controllers\Docente\ComunicadoController::class, 'show'])->name('docente.comunicados.show');

    // Seguimiento (Kardex)
    Route::get('/seguimientos/{estudiante_id}', [App\Http\Controllers\Docente\SeguimientoController::class, 'index'])->name('docente.seguimientos.index');
    Route::post('/seguimientos/{estudiante_id}', [App\Http\Controllers\Docente\SeguimientoController::class, 'store'])->name('docente.seguimientos.store');
    Route::put('/seguimientos/{id}', [App\Http\Controllers\Docente\SeguimientoController::class, 'update'])->name('docente.seguimientos.update');
    Route::delete('/seguimientos/{id}', [App\Http\Controllers\Docente\SeguimientoController::class, 'destroy'])->name('docente.seguimientos.destroy');
});

// Admin Extra Routes
Route::middleware(['auth', 'role:administrador'])->prefix('admin')->group(function () {
     // ... existing routes ...
     // Comunicados
     Route::resource('comunicados', App\Http\Controllers\Admin\ComunicadoController::class, ['as' => 'admin']);
});
