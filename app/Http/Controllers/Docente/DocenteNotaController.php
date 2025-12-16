<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AsignacionDocente;
use App\Models\AnioAcademico;
use App\Models\InscripcionEst;
use App\Models\Nota;
use App\Models\Profesor;
use Barryvdh\DomPDF\Facade\Pdf;

class DocenteNotaController extends Controller
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

        return view('docente.notas.index', compact('asignaciones'));
    }

    public function show($asignacionId)
    {
        $user = Auth::user();
        $profesor = Profesor::where('user_id', $user->id)->firstOrFail();
        
        $asignacion = AsignacionDocente::with(['materia', 'paralelo.curso'])
            ->findOrFail($asignacionId);

        if ($asignacion->profesor_id !== $profesor->id) {
            abort(403);
        }

        $estudiantes = InscripcionEst::where('paralelo_id', $asignacion->paralelo_id)
            ->where('anio_academico_id', $asignacion->anio_academico_id)
            ->where('estado', 'activo')
            ->with('estudiante')
            ->get()
            ->sortBy('estudiante.apellido_paterno');

        return view('docente.notas.estudiantes', compact('asignacion', 'estudiantes'));
    }

    public function registrar(Request $request, $asignacionId, $estudianteId)
    {
        $user = Auth::user();
        $profesor = Profesor::where('user_id', $user->id)->firstOrFail();
        
        $asignacion = AsignacionDocente::with(['materia', 'paralelo.curso'])
            ->findOrFail($asignacionId);

        if ($asignacion->profesor_id !== $profesor->id) {
            abort(403);
        }

        $inscripcion = InscripcionEst::with('estudiante')->findOrFail($estudianteId);
        
        $trimestreActual = $request->query('trimestre', 1);

        // Obtener nota existente para este trimestre
        $nota = Nota::firstOrNew([
            'inscripcion_id' => $inscripcion->id,
            'materia_id' => $asignacion->materia_id,
            'trimestre' => $trimestreActual
        ]);

        // Periodos (Trimestres)
        $periodos = [
            (object)['id_periodo' => '1', 'nombre' => '1er Trimestre'],
            (object)['id_periodo' => '2', 'nombre' => '2do Trimestre'],
            (object)['id_periodo' => '3', 'nombre' => '3er Trimestre'],
        ];

        return view('docente.notas.registrar', compact('asignacion', 'inscripcion', 'nota', 'periodos', 'trimestreActual'));
    }

    public function store(Request $request, $asignacionId, $estudianteId)
    {
        $user = Auth::user();
        $profesor = Profesor::where('user_id', $user->id)->firstOrFail();
        $asignacion = AsignacionDocente::findOrFail($asignacionId);

        if ($asignacion->profesor_id !== $profesor->id) {
            abort(403);
        }

        $validated = $request->validate([
            'trimestre' => 'required|string|in:1,2,3',
            'nota_ser' => 'required|numeric|min:0|max:100',
            'nota_saber' => 'required|numeric|min:0|max:100',
            'nota_hacer' => 'required|numeric|min:0|max:100',
            'nota_decidir' => 'required|numeric|min:0|max:100',
            'autoevaluacion' => 'required|numeric|min:0|max:100',
        ]);

        // Calcular promedio ponderado
        // Ser: 10%, Saber: 35%, Hacer: 35%, Decidir: 10%, Autoevaluación: 10%
        $notaFinal = ($request->nota_ser * 0.10) + 
                     ($request->nota_saber * 0.35) + 
                     ($request->nota_hacer * 0.35) + 
                     ($request->nota_decidir * 0.10) + 
                     ($request->autoevaluacion * 0.10);

        // Redondear a entero (regla de oro en libretas escolares bolivianas usualmente es entero)
        $notaFinalEntera = round($notaFinal);

        // Obtener la inscripción para extraer el estudiante_id
        $inscripcion = InscripcionEst::findOrFail($estudianteId);

        $validated['estudiante_id'] = $inscripcion->estudiante_id;
        $validated['inscripcion_id'] = $estudianteId; 
        $validated['materia_id'] = $asignacion->materia_id;
        $validated['nota_final'] = $notaFinalEntera;
        $validated['literal'] = $this->convertirNumeroALiteral($notaFinalEntera);

        Nota::updateOrCreate(
            [
                'inscripcion_id' => $estudianteId,
                'materia_id' => $asignacion->materia_id,
                'trimestre' => $request->trimestre
            ],
            $validated
        );

        // Notificar a los tutores
        try {
            $estudiante = \App\Models\Estudiante::with('tutores.user')->find($inscripcion->estudiante_id);
            if ($estudiante && $estudiante->tutores) {
                $notificationService = app(\App\Services\FirebaseNotificationService::class);
                foreach ($estudiante->tutores as $tutor) {
                    if ($tutor->user_id && $tutor->user && $tutor->user->fcm_token) {
                        $title = "Notas Actualizadas";
                        $body = "Se han actualizado las notas de {$estudiante->nombres} en {$asignacion->materia->nombre}";
                        $notificationService->sendToUser($tutor->user_id, $title, $body, ['type' => 'grade_update']);
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error enviando notificación de nota: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Notas guardadas correctamente');
    }

    private function convertirNumeroALiteral($numero)
    {
        // Implementación simple, se puede mejorar con una librería
        if ($numero >= 51) return 'APROBADO';
        return 'REPROBADO';
    }

    public function descargar($asignacionId, $estudianteId)
    {
        $user = Auth::user();
        $profesor = Profesor::where('user_id', $user->id)->firstOrFail();
        
        $asignacion = AsignacionDocente::with(['materia.area', 'paralelo.curso'])
            ->findOrFail($asignacionId);

        if ($asignacion->profesor_id !== $profesor->id) {
            abort(403);
        }

        $inscripcion = InscripcionEst::with('estudiante')->findOrFail($estudianteId);
        
        $notas = Nota::where('inscripcion_id', $inscripcion->id)
            ->where('materia_id', $asignacion->materia_id)
            ->get();

        // Organizar notas por trimestre
        $notasPorTrimestre = [
            1 => $notas->firstWhere('trimestre', '1'),
            2 => $notas->firstWhere('trimestre', '2'),
            3 => $notas->firstWhere('trimestre', '3'),
        ];

        // Calcular Promedio Anual
        $sumaPromedios = 0;
        $trimestresConNota = 0;

        foreach ($notasPorTrimestre as $nota) {
            if ($nota) {
                $sumaPromedios += $nota->nota_final;
                $trimestresConNota++;
            }
        }

        $promedioAnual = $trimestresConNota > 0 ? round($sumaPromedios / 3) : 0; // Regla de 3: Suma / 3 (incluso si falta un trimestre, usualmente se divide entre 3 al final de gestión, pero para parciales podría ser diferente. El usuario dijo "sumas los tres trimestres y despues lo divides entre 3")

        $pdf = Pdf::loadView('docente.notas.reporte-pdf', compact('asignacion', 'inscripcion', 'notasPorTrimestre', 'profesor', 'promedioAnual'));
        $pdf->setPaper('letter', 'landscape'); // Libreta suele ser horizontal o vertical? La imagen parece horizontal (landscape).
        
        return $pdf->download('libreta-' . $inscripcion->estudiante->codigo_estudiante . '.pdf');
    }
}
