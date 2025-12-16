<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paralelo;
use App\Models\InscripcionEst;
use App\Models\Materia;
use App\Models\Nota;
use App\Models\Asistencia;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        $paralelos = Paralelo::where('estado', true)->with('curso')->get();
        return view('admin.reportes.index', compact('paralelos'));
    }

    public function centralizador(Request $request) 
    {
        $paraleloId = $request->paralelo_id;
        $paralelo = Paralelo::with('curso')->findOrFail($paraleloId);
        $anioActual = anio_actual();

        // Obtener estudiantes
        $inscripciones = InscripcionEst::where('paralelo_id', $paraleloId)
            ->where('anio_academico_id', $anioActual->id)
            ->where('estado', 'activo')
            ->with('estudiante')
            ->get()
            ->sortBy('estudiante.apellido_paterno');

        // Obtener materias del curso
        $materias = $paralelo->curso->materias;

        // Estructurar matriz de notas
        // [estudiante_id][materia_id] = nota_final
        $matriz = [];
        $promedios = [];

        foreach ($inscripciones as $inscripcion) {
            $notas = Nota::where('inscripcion_id', $inscripcion->id)->get();
            $suma = 0;
            $count = 0;
            foreach ($notas as $nota) {
                // Simplificación: Tomamos el promedio de los 4 bimestres si es centralizador final
                // O el del bimestre seleccionado. Aquí asumimos el promedio anual.
                $item = $matriz[$inscripcion->id][$nota->materia_id] ?? 0;
                // Si hay nota, la usamos. Podríamos filtrar por bimestre si el request lo pide.
                $matriz[$inscripcion->id][$nota->materia_id] = $nota->nota_final; 
                $suma += $nota->nota_final;
                $count++;
            }
            $promedios[$inscripcion->id] = $count > 0 ? round($suma / $count, 2) : 0;
        }

        if ($request->has('descargar')) {
            $pdf = Pdf::loadView('admin.reportes.centralizador_pdf', compact('paralelo', 'inscripciones', 'materias', 'matriz', 'promedios'))->setPaper('a4', 'landscape');
            return $pdf->download('centralizador.pdf');
        }

        return view('admin.reportes.centralizador', compact('paralelo', 'inscripciones', 'materias', 'matriz', 'promedios'));
    }

    public function asistenciaEstadisticas(Request $request)
    {
        $paraleloId = $request->paralelo_id;
        $paralelo = Paralelo::with('curso')->findOrFail($paraleloId);
        $anioActual = anio_actual();

        $inscripciones = InscripcionEst::where('paralelo_id', $paraleloId)
            ->where('anio_academico_id', $anioActual->id)
            ->where('estado', 'activo')
            ->with(['estudiante', 'asistencias'])
            ->get();

        $estadisticas = [];

        foreach ($inscripciones as $inscripcion) {
            $total = $inscripcion->asistencias->count();
            $presentes = $inscripcion->asistencias->where('estado', 'presente')->count();
            $faltas = $inscripcion->asistencias->where('estado', 'falta')->count();
            $licencias = $inscripcion->asistencias->where('estado', 'licencia')->count();
            $atrasos = $inscripcion->asistencias->where('estado', 'atraso')->count();

            $porcentajeAsistencia = $total > 0 ? round(($presentes / $total) * 100, 1) : 0;

            $estadisticas[$inscripcion->id] = [
                'total' => $total,
                'presentes' => $presentes,
                'faltas' => $faltas,
                'licencias' => $licencias,
                'atrasos' => $atrasos,
                'porcentaje' => $porcentajeAsistencia
            ];
        }

        if ($request->has('descargar')) {
            $pdf = Pdf::loadView('admin.reportes.asistencia_pdf', compact('paralelo', 'inscripciones', 'estadisticas'));
            return $pdf->download('reporte_asistencia.pdf');
        }

        return view('admin.reportes.asistencia', compact('paralelo', 'inscripciones', 'estadisticas'));
    }
}
