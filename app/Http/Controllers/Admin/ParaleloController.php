<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paralelo;
use App\Models\Curso;
use App\Models\AnioAcademico;
use App\Models\Profesor;
use Illuminate\Http\Request;

class ParaleloController extends Controller
{
    public function index()
    {
        $paralelos = Paralelo::with(['curso', 'anioAcademico', 'asesor'])->withCount('inscripciones')->get();
        $cursos = Curso::where('estado', true)->get();
        return view('admin.paralelos.index', compact('paralelos', 'cursos'));
    }

    public function create()
    {
        $cursos = Curso::where('estado', true)->get();
        $profesores = Profesor::where('estado', true)->with('user')->get();
        return view('admin.paralelos.create', compact('cursos', 'profesores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'curso_id' => 'required|exists:cursos,id',
            'cupo_maximo' => 'required|integer|min:1',
            'turno' => 'required|string|in:mañana,tarde,noche',
            'aula' => 'nullable|string|max:40',
            'asesor_id' => 'nullable|exists:profesores,id',
        ]);

        $anioActual = AnioAcademico::where('estado', true)->first();
        $curso = Curso::findOrFail($validated['curso_id']);
        
        $validated['anio_academico_id'] = $anioActual->id;
        $validated['codigo'] = $curso->codigo . '-' . $validated['nombre'];

        if (Paralelo::where('codigo', $validated['codigo'])->exists()) {
            return response()->json([
                'errors' => ['nombre' => ['Ya existe un paralelo con este nombre (' . $validated['nombre'] . ') para el curso seleccionado.']]
            ], 422);
        }

        Paralelo::create($validated);

        session()->flash('success', 'Paralelo creado exitosamente');
        return response()->json(['message' => 'Paralelo creado exitosamente']);
    }

    public function edit($id)
    {
        $paralelo = Paralelo::findOrFail($id);
        $cursos = Curso::where('estado', true)->get();
        $profesores = Profesor::where('estado', true)->with('user')->get();
        return view('admin.paralelos.edit', compact('paralelo', 'cursos', 'profesores'));
    }

    public function update(Request $request, $id)
    {
        $paralelo = Paralelo::findOrFail($id);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'curso_id' => 'required|exists:cursos,id',
            'cupo_maximo' => 'required|integer|min:1',
            'turno' => 'required|string|in:mañana,tarde,noche',
            'aula' => 'nullable|string|max:40',
            'asesor_id' => 'nullable|exists:profesores,id',
        ]);

        // Check for duplicate code (excluding current record)
        $newCodigo = $paralelo->curso->codigo . '-' . $validated['nombre'];
        // If course changed, we need to fetch new course code, but assuming course_id is validated above:
        if ($paralelo->curso_id != $validated['curso_id']) {
             $curso = Curso::findOrFail($validated['curso_id']);
             $newCodigo = $curso->codigo . '-' . $validated['nombre'];
        }

        // We should check duplication if name or course changed
        if (Paralelo::where('codigo', $newCodigo)->where('id', '!=', $id)->exists()) {
             return response()->json([
                'errors' => ['nombre' => ['Ya existe un paralelo con este nombre para el curso seleccionado.']]
            ], 422);
        }
        
        $validated['codigo'] = $newCodigo; // Ensure code is updated if name changed

        $paralelo->update($validated);

        session()->flash('success', 'Paralelo actualizado exitosamente');
        return response()->json(['message' => 'Paralelo actualizado exitosamente']);
    }

    public function destroy($id)
    {
        $paralelo = Paralelo::findOrFail($id);
        $paralelo->delete();

        return redirect()->route('admin.paralelos')->with('success', 'Paralelo eliminado exitosamente');
    }
}
