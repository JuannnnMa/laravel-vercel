<?php
// ... en routes/web.php dentro de grupo admin
Route::get('reportes', [App\Http\Controllers\Admin\ReporteController::class, 'index'])->name('admin.reportes.index');
Route::get('reportes/centralizador', [App\Http\Controllers\Admin\ReporteController::class, 'centralizador'])->name('admin.reportes.centralizador');
Route::get('reportes/asistencia', [App\Http\Controllers\Admin\ReporteController::class, 'asistenciaEstadisticas'])->name('admin.reportes.asistencia');
