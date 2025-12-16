<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Profesor;
use App\Models\AsignacionDocente;
use Illuminate\Support\Facades\Auth;

class DocenteDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profesor = Profesor::where('user_id', $user->id)->firstOrFail();
        $anioActual = anio_actual();

        if (!$anioActual) {
            return view('docente.dashboard', [
                'profesor' => $profesor, 
                'asignaciones' => collect(),
                'anioActual' => null
            ])->with('error', 'No hay año académico activo.');
        }
        
        // Obtener asignaciones activas del profesor para el año seleccionado
        $asignaciones = AsignacionDocente::where('profesor_id', $profesor->id)
            ->where('anio_academico_id', $anioActual->id)
            ->where('estado', true)
            ->with(['materia', 'paralelo.curso', 'anioAcademico'])
            ->get();

        return view('docente.dashboard', compact('profesor', 'asignaciones', 'anioActual'));
    }
}
