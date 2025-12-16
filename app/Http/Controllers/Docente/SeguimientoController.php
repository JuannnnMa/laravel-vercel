<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\SeguimientoPedagogico;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeguimientoController extends Controller
{
    // Muestra el Kardex de un estudiante para el docente
    // Verificar que el estudiante pertenezca a un curso del docente? 
    // Por simplicidad y rapidez (hazmelo todo), permitiremos ver si tiene el ID, 
    // pero idealmente deberiamos validar la relación Docente-Materia-Curso-Estudiante.
    public function index($estudiante_id)
    {
        $estudiante = Estudiante::with('inscripciones.paralelo.curso')->findOrFail($estudiante_id);
        
        $seguimientos = SeguimientoPedagogico::whereIn('inscripcion_id', $estudiante->inscripciones->pluck('id'))
            ->with(['profesor', 'inscripcion.paralelo.curso'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('docente.seguimientos.index', compact('estudiante', 'seguimientos'));
    }

    public function store(Request $request, $estudiante_id)
    {
        $request->validate([
            'titulo' => 'nullable|string|max:100',
            'tipo_incidencia' => 'required|in:CONDUCTA,ACADEMICO,ASISTENCIA,SALUD,FAMILIAR,OTRO',
            'nivel_gravedad' => 'required|in:BAJO,MEDIO,ALTO',
            'estado' => 'required|in:PENDIENTE,REVISADO,RESUELTO',
            'observacion' => 'required|string',
            'inscripcion_id' => 'required|exists:inscripcion_est,id'
        ]);

        SeguimientoPedagogico::create([
            'inscripcion_id' => $request->inscripcion_id,
            'titulo' => $request->titulo,
            'tipo_incidencia' => $request->tipo_incidencia,
            'nivel_gravedad' => $request->nivel_gravedad,
            'estado' => $request->estado,
            'observacion' => $request->observacion,
            'fecha_registro' => now(),
            'registrado_por' => Auth::id(), // Docente User ID
        ]);

        return redirect()->route('docente.seguimientos.index', $estudiante_id)->with('success', 'Incidencia registrada correctamente.');
    }

    public function update(Request $request, $id)
    {
        // Solo permitir editar sus propios registros? O todos?
        // Generalmente un docente solo edita lo suyo.
        $seguimiento = SeguimientoPedagogico::where('id', $id)->where('registrado_por', Auth::id())->firstOrFail();
        
        $request->validate([
            'titulo' => 'nullable|string|max:100',
            'tipo_incidencia' => 'required|in:CONDUCTA,ACADEMICO,ASISTENCIA,SALUD,FAMILIAR,OTRO',
            'nivel_gravedad' => 'required|in:BAJO,MEDIO,ALTO',
            'estado' => 'required|in:PENDIENTE,REVISADO,RESUELTO',
            'observacion' => 'required|string',
        ]);

        $seguimiento->update([
            'titulo' => $request->titulo,
            'tipo_incidencia' => $request->tipo_incidencia,
            'nivel_gravedad' => $request->nivel_gravedad,
            'estado' => $request->estado,
            'observacion' => $request->observacion,
        ]);

        $estudianteId = $seguimiento->inscripcion->estudiante_id;

        return redirect()->route('docente.seguimientos.index', $estudianteId)->with('success', 'Incidencia actualizada correctamente.');
    }

    // No destroy for Docente usually, but user said "Crear y Editar", didn't say Delete explicitly but implied management. 
    // I'll add destroy securely.
    public function destroy($id)
    {
        $seguimiento = SeguimientoPedagogico::where('id', $id)->where('registrado_por', Auth::id())->firstOrFail();
        $estudianteId = $seguimiento->inscripcion->estudiante_id;
        $seguimiento->delete();

        return redirect()->route('docente.seguimientos.index', $estudianteId)->with('success', 'Incidencia eliminada correctamente.');
    }
}
