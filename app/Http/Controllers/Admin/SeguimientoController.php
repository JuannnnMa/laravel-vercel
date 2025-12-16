<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeguimientoPedagogico;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeguimientoController extends Controller
{
    public function index($estudiante_id)
    {
        $estudiante = Estudiante::with('inscripciones.paralelo.curso')->findOrFail($estudiante_id);
        
        // Obtener historial de todas las inscripciones del estudiante
        // O solo de la gestión actual? Mejor histório completo.
        $seguimientos = SeguimientoPedagogico::whereIn('inscripcion_id', $estudiante->inscripciones->pluck('id'))
            ->with(['profesor', 'inscripcion.paralelo.curso'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.seguimientos.index', compact('estudiante', 'seguimientos'));
    }

    public function store(Request $request, $estudiante_id)
    {
        $request->validate([
            'titulo' => 'nullable|string|max:100',
            'tipo_incidencia' => 'required|in:CONDUCTA,ACADEMICO,ASISTENCIA,SALUD,FAMILIAR,OTRO',
            'nivel_gravedad' => 'required|in:BAJO,MEDIO,ALTO',
            'estado' => 'required|in:PENDIENTE,REVISADO,RESUELTO',
            'observacion' => 'required|string',
            'inscripcion_id' => 'required|exists:inscripcion_est,id' // ID de la inscripción actual
        ]);

        SeguimientoPedagogico::create([
            'inscripcion_id' => $request->inscripcion_id,
            'titulo' => $request->titulo,
            'tipo_incidencia' => $request->tipo_incidencia,
            'nivel_gravedad' => $request->nivel_gravedad,
            'estado' => $request->estado,
            'observacion' => $request->observacion,
            'fecha_registro' => now(),
            'registrado_por' => Auth::id(), // Admin User ID
        ]);

        return redirect()->route('admin.seguimientos.index', $estudiante_id)->with('success', 'Incidencia registrada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $seguimiento = SeguimientoPedagogico::findOrFail($id);
        
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

        // Get estudiante ID from relation to redirect back correctly
        $estudianteId = $seguimiento->inscripcion->estudiante_id;

        return redirect()->route('admin.seguimientos.index', $estudianteId)->with('success', 'Incidencia actualizada correctamente.');
    }

    public function destroy($id)
    {
        $seguimiento = SeguimientoPedagogico::findOrFail($id);
        $estudianteId = $seguimiento->inscripcion->estudiante_id;
        $seguimiento->delete();

        return redirect()->route('admin.seguimientos.index', $estudianteId)->with('success', 'Incidencia eliminada correctamente.');
    }
}
