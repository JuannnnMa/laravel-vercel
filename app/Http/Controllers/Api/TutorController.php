<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tutor;
use App\Models\Estudiante;
use App\Models\AnioAcademico;
use App\Models\Calificacion;
use App\Models\Asistencia;
use App\Models\Comunicado;

class TutorController extends Controller
{
    public function dashboard(Request $request)
    {
        // El usuario autenticado es un User, no un Tutor directamente
        $user = $request->user();
        
        // Buscar el perfil de Tutor asociado por user_id
        $tutor = Tutor::where('user_id', $user->id)->first();
        
        if (!$tutor) {
            return response()->json([
                'success' => false,
                'message' => 'Perfil de tutor no encontrado',
                'hijos' => []
            ], 404);
        }
        
        // Obtener hijos del tutor a través de la tabla parentescos
        $hijos = Estudiante::whereHas('tutores', function($q) use ($tutor) {
            $q->where('parentescos.tutor_id', $tutor->id);
        })->with(['inscripciones' => function($q) {
            // Obtener solo inscripciones activas del año académico actual
            $q->where('estado', 'activo')
              ->with(['paralelo.curso', 'anioAcademico'])
              ->latest('fecha_inscripcion');
        }])->get();

        // Formatear los datos para la app móvil
        $hijosFormateados = $hijos->map(function($hijo) {
            $inscripcionActual = $hijo->inscripciones->first();
            
            return [
                'id_estudiante' => $hijo->id,
                'nombres' => $hijo->nombres,
                'apellido_paterno' => $hijo->apellido_paterno,
                'apellido_materno' => $hijo->apellido_materno,
                'ci' => $hijo->ci,
                'foto_url' => $hijo->foto_url,
                'curso' => $inscripcionActual ? $inscripcionActual->paralelo->curso->nombre : null,
                'paralelo' => $inscripcionActual ? $inscripcionActual->paralelo->nombre : null,
            ];
        });

        return response()->json([
            'success' => true,
            'hijos' => $hijosFormateados,
            'tutor' => [
                'id' => $tutor->id,
                'nombres' => $tutor->nombres,
                'apellido_paterno' => $tutor->apellido_paterno,
                'apellido_materno' => $tutor->apellido_materno,
                'email' => $tutor->email,
            ]
        ]);
    }

    public function notas(Request $request, $estudianteId)
    {
        $user = $request->user();
        $tutor = Tutor::where('user_id', $user->id)->first();
        
        if (!$tutor) {
            return response()->json(['success' => false, 'message' => 'Tutor no encontrado'], 404);
        }
        
        // Verificar que el estudiante sea hijo del tutor
        $esHijo = Estudiante::where('id', $estudianteId)
            ->whereHas('tutores', function($q) use ($tutor) {
                $q->where('parentescos.tutor_id', $tutor->id);
            })->exists();

        if (!$esHijo) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $estudiante = Estudiante::findOrFail($estudianteId);
        
        // Obtener la inscripción actual
        $inscripcion = $estudiante->inscripciones()
            ->where('estado', 'activo')
            ->latest('fecha_inscripcion')
            ->first();

        if (!$inscripcion) {
            return response()->json(['success' => false, 'message' => 'Estudiante no inscrito'], 404);
        }

        // Obtener notas agrupadas por materia
        $notas = $inscripcion->notas()
            ->with(['materia'])
            ->get()
            ->groupBy('materia.nombre');

        return response()->json([
            'success' => true,
            'estudiante' => $estudiante,
            'notas' => $notas
        ]);
    }

    public function asistencia(Request $request, $estudianteId)
    {
        $user = $request->user();
        $tutor = Tutor::where('user_id', $user->id)->first();
        
        if (!$tutor) {
            return response()->json(['success' => false, 'message' => 'Tutor no encontrado'], 404);
        }
        
        // Verificar relación
        $esHijo = Estudiante::where('id', $estudianteId)
            ->whereHas('tutores', function($q) use ($tutor) {
                $q->where('parentescos.tutor_id', $tutor->id);
            })->exists();

        if (!$esHijo) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $estudiante = Estudiante::findOrFail($estudianteId);
        
        // Obtener la inscripción actual
        $inscripcion = $estudiante->inscripciones()
            ->where('estado', 'activo')
            ->latest('fecha_inscripcion')
            ->first();

        if (!$inscripcion) {
            return response()->json(['success' => false, 'message' => 'Estudiante no inscrito'], 404);
        }

        $asistencias = $inscripcion->asistencias()
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'estudiante' => $estudiante,
            'asistencias' => $asistencias
        ]);
    }

    public function markAsSeen(Request $request)
    {
        // Aquí registramos que el tutor vio la info
        // Podríamos tener una tabla 'notificacion_lectura' o similar
        
        return response()->json([
            'success' => true,
            'message' => 'Marcado como visto'
        ]);
    }

    public function comunicados(Request $request)
    {
        $user = $request->user();
        
        // Usamos el scope del modelo Comunicado
        $comunicados = Comunicado::paraUsuario($user)
            ->latest()
            ->get()
            ->map(function($c) {
                return [
                    'id' => $c->id,
                    'titulo' => $c->titulo,
                    'mensaje' => $c->mensaje,
                    'tipo' => $c->tipo,
                    'fecha' => $c->created_at->format('d/m/Y H:i'),
                    'fecha_evento' => $c->fecha_evento ? $c->fecha_evento->format('d/m/Y H:i') : null,
                    'creador' => $c->creador ? ($c->creador->nombres . ' ' . $c->creador->apellidos) : 'Administración'
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $comunicados
        ]);
    }
}
