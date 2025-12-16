<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::withCount('paralelos')->orderBy('grado')->get();
        return view('admin.cursos.index', compact('cursos'));
    }

    public function create()
    {
        return view('admin.cursos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'nivel' => 'required|string|max:50',
            'grado' => 'required|integer|min:1|max:6',
            'descripcion' => 'nullable|string|max:200',
        ]);

        $validated['codigo'] = substr($validated['nivel'], 0, 1) . $validated['grado']; // Ejemplo: P1, S2
        
        Curso::create($validated);

        session()->flash('success', 'Curso creado exitosamente');
        return response()->json(['message' => 'Curso creado exitosamente']);
    }

    public function edit($id)
    {
        $curso = Curso::findOrFail($id);
        return view('admin.cursos.edit', compact('curso'));
    }

    public function update(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'nivel' => 'required|string|max:50',
            'grado' => 'required|integer|min:1|max:6',
            'descripcion' => 'nullable|string|max:200',
        ]);

        $curso->update($validated);

        session()->flash('success', 'Curso actualizado exitosamente');
        return response()->json(['message' => 'Curso actualizado exitosamente']);
    }

    public function destroy($id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();

        return redirect()->route('admin.cursos')->with('success', 'Curso eliminado exitosamente');
    }
}
