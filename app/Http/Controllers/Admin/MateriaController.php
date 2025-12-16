<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Area;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with('area')->orderBy('nombre')->get();
        $areas = Area::where('estado', true)->get();
        return view('admin.materias.index', compact('materias', 'areas'));
    }

    public function create()
    {
        $areas = Area::where('estado', true)->get();
        return view('admin.materias.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|unique:materias,codigo',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:250',
            'area_id' => 'required|exists:areas,id',
        ]);

        $validated['estado'] = true;
        Materia::create($validated);

        session()->flash('success', 'Materia creada exitosamente');
        return response()->json(['message' => 'Materia creada exitosamente']);
    }

    public function edit($id)
    {
        $materia = Materia::findOrFail($id);
        $areas = Area::where('estado', true)->get();
        return view('admin.materias.edit', compact('materia', 'areas'));
    }

    public function update(Request $request, $id)
    {
        $materia = Materia::findOrFail($id);
        
        $validated = $request->validate([
            'codigo' => 'required|unique:materias,codigo,' . $id,
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:250',
            'area_id' => 'required|exists:areas,id',
        ]);

        $materia->update($validated);

        session()->flash('success', 'Materia actualizada exitosamente');
        return response()->json(['message' => 'Materia actualizada exitosamente']);
    }

    public function destroy($id)
    {
        $materia = Materia::findOrFail($id);
        $materia->update(['estado' => false]);

        return redirect()->route('admin.materias')->with('success', 'Materia desactivada exitosamente');
    }

    public function activate($id)
    {
        $materia = Materia::findOrFail($id);
        $materia->update(['estado' => true]);

        return redirect()->route('admin.materias')->with('success', 'Materia activada exitosamente');
    }
}
