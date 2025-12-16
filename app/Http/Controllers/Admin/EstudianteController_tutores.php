
    // Gestion de Tutores
    public function tutores($estudiante_id)
    {
        $estudiante = Estudiante::with('tutores')->findOrFail($estudiante_id);
        $tutoresDisponibles = \App\Models\Tutor::where('estado', true)
            ->orderBy('apellido_paterno')
            ->get();
        
        return view('admin.estudiantes.tutores', compact('estudiante', 'tutoresDisponibles'));
    }

    public function attachTutor(Request $request, $estudiante_id)
    {
        $request->validate([
            'tutor_id' => 'required|exists:tutores,id',
            'tipo_parentesco' => 'required|string|max:50',
            'es_principal' => 'boolean'
        ]);

        $estudiante = Estudiante::findOrFail($estudiante_id);
        
        // Verificar si ya existe la relacion
        $existe = $estudiante->tutores()->where('tutor_id', $request->tutor_id)->exists();
        if ($existe) {
            return redirect()->back()->with('error');
        }

        $estudiante->tutores()->attach($request->tutor_id, [
            'tipo_parentesco' => $request->tipo_parentesco,
            'es_principal' => $request->has('es_principal')
        ]);

        return redirect()->route('admin.estudiantes.tutores', $estudiante_id)->with('success','tutor agregado correctamente');
    }

    public function detachTutor($parentesco_id)
    {
        $parentesco = \App\Models\Parentesco::findOrFail($parentesco_id);
        $estudiante_id = $parentesco->estudiante_id;
        $parentesco->delete();

        return redirect()->route('admin.estudiantes.tutores', $estudiante_id)->with('success','tutor desvinculado correctamente');
    }
}
