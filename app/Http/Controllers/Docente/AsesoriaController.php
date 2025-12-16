<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;

class AsesoriaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->profesor) {
            return redirect()->back()->with('error', 'No tienes un perfil de profesor asociado.');
        }

        $profesor = $user->profesor;
        
        // Obtener paralelos donde es asesor
        $paralelosAsesorados = $profesor->paralelosAsesorados()->with(['curso', 'anioAcademico'])->get();

        return view('docente.asesoria.index', compact('paralelosAsesorados'));
    }
    public function show($id)
    {
        $user = Auth::user();
        if (!$user->profesor) {
            return redirect()->back()->with('error', 'No tienes un perfil de profesor asociado.');
        }

        $profesor = $user->profesor;
        $paralelo = $profesor->paralelosAsesorados()->where('paralelos.id', $id)->with(['curso', 'anioAcademico'])->firstOrFail();

        // Obtener estudiantes inscritos en este paralelo
        $estudiantes = $paralelo->inscripciones()->with('estudiante')->get();

        return view('docente.asesoria.show', compact('paralelo', 'estudiantes'));
    }
}
