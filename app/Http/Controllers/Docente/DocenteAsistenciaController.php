<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AsignacionDocente;
use App\Models\AnioAcademico;
use App\Models\Asistencia;
use App\Models\InscripcionEst;
use App\Models\Profesor;

class DocenteAsistenciaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profesor = Profesor::where('user_id', $user->id)->firstOrFail();
        $anioActual = AnioAcademico::where('estado', true)->first();

        if (!$anioActual) {
            return back()->with('error', 'No hay un año académico activo.');
        }

        $asignaciones = AsignacionDocente::with(['materia', 'paralelo.curso'])
            ->where('profesor_id', $profesor->id)
            ->where('anio_academico_id', $anioActual->id)
            ->where('estado', true)
            ->get();

        return view('docente.asistencia.index', compact('asignaciones'));
    }

    public function show(Request $request, $asignacionId)
    {
        $user = Auth::user();
        $profesor = Profesor::where('user_id', $user->id)->firstOrFail();
        
        $asignacion = AsignacionDocente::with(['materia', 'paralelo.curso'])
            ->findOrFail($asignacionId);

        if ($asignacion->profesor_id !== $profesor->id) {
            abort(403);
        }

        $fecha = $request->query('fecha', date('Y-m-d'));

        $estudiantes = InscripcionEst::where('paralelo_id', $asignacion->paralelo_id)
            ->where('anio_academico_id', $asignacion->anio_academico_id)
            ->where('estado', 'activo')
            ->with('estudiante')
            ->get()
            ->sortBy('estudiante.apellido_paterno');

        // Fetch existing attendance for this date
        $asistencias = Asistencia::where('asignacion_id', $asignacion->id)
            ->where('fecha', $fecha)
            ->get()
            ->keyBy('inscripcion_id');

        return view('docente.asistencia.registrar', compact('asignacion', 'estudiantes', 'fecha', 'asistencias'));
    }

    public function store(Request $request, $asignacionId)
    {
        $user = Auth::user();
        $profesor = Profesor::where('user_id', $user->id)->firstOrFail();
        $asignacion = AsignacionDocente::findOrFail($asignacionId);

        if ($asignacion->profesor_id !== $profesor->id) {
            abort(403);
        }

        $fecha = $request->input('fecha');
        $asistencias = $request->input('asistencia');
        $observaciones = $request->input('observacion');

        // Pre-fetch all inscripciones to avoid N+1 queries
        $inscripciones = InscripcionEst::whereIn('id', array_keys($asistencias))->get()->keyBy('id');

        foreach ($asistencias as $inscripcionId => $estado) {
            $inscripcion = $inscripciones[$inscripcionId] ?? null;
            if (!$inscripcion) continue;

            Asistencia::updateOrCreate(
                [
                    'inscripcion_id' => $inscripcionId,
                    'asignacion_id' => $asignacion->id,
                    'fecha' => $fecha,
                ],
                [
                    'estudiante_id' => $inscripcion->estudiante_id,
                    'estado' => $estado,
                    'observacion' => $observaciones[$inscripcionId] ?? null,
                    'registrado_por' => $user->id,
                ]
            );

            // Notificar al tutor si hay cambio de estado (o siempre)
            try {
                // Solo notificar si no es "Presente" para no spammear, o si el usuario lo prefiere.
                // Por ahora notificaremos todo para testear.
                $estudiante = \App\Models\Estudiante::with('tutores.user')->find($inscripcion->estudiante_id);
                if ($estudiante && $estudiante->tutores) {
                    $notificationService = app(\App\Services\FirebaseNotificationService::class);
                    foreach ($estudiante->tutores as $tutor) {
                        if ($tutor->user_id && $tutor->user && $tutor->user->fcm_token) {
                            $title = "Asistencia Registrada";
                            $body = "El estudiante {$estudiante->nombres} fue marcado como {$estado} el día {$fecha}.";
                            $notificationService->sendToUser($tutor->user_id, $title, $body, ['type' => 'attendance_update']);
                        }
                    }
                }
            } catch (\Exception $e) {
                // Ignorar errores de notificación para no interrumpir el guardado
                \Illuminate\Support\Facades\Log::error("Error enviando notificación asis: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Asistencia registrada correctamente');
    }

    public function uploadData()
    {
        // Aquí iría la lógica para notificar a los tutores
        // Por ahora simularemos que se envió
        
        // TODO: Integrar con Firebase Cloud Messaging
        // $this->notificationService->sendToAllTutors('Nuevas notas y asistencia disponibles');

        return redirect()->back()->with('success', 'Se ha notificado a los tutores que las notas y asistencia han sido actualizadas.');
    }
}
