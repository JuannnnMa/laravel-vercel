<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profesor;
use App\Models\Materia;
use App\Models\AsignacionDocente;
use App\Models\AnioAcademico;
use App\Models\Paralelo;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfesorController extends Controller
{
    public function index()
    {
        $profesores = Profesor::with(['asignaciones.materia', 'asignaciones.paralelo'])
            ->orderBy('apellido_paterno')
            ->get(); // Changed paginate to get
        
        return view('admin.profesores.index', compact('profesores'));
    }

    public function create()
    {
        return view('admin.profesores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ci' => 'required|unique:profesores,ci',
            'nombres' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:40',
            'apellido_materno' => 'required|string|max:40',
            'celular' => 'nullable|string|max:20',
            'email' => 'required|email|unique:profesores,email',
            'direccion' => 'nullable|string',
            'especialidad' => 'nullable|string|max:100',
            'fecha_ingreso' => 'required|date',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($validated) {
            // Generar código de profesor
            $ultimoProfesor = Profesor::latest('id')->first();
            $numero = $ultimoProfesor ? $ultimoProfesor->id + 1 : 1;
            $validated['codigo_profesor'] = 'PROF' . str_pad($numero, 4, '0', STR_PAD_LEFT);

            // Crear usuario
            $profesorRole = Role::where('nombre', 'profesor')->first();
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $profesorRole->id,
            ]);

            // Crear profesor
            $validated['user_id'] = $user->id;
            $profesor = Profesor::create($validated);

            // Actualizar relación polimórfica
            $user->update([
                'userable_type' => Profesor::class,
                'userable_id' => $profesor->id,
            ]);
        });
        
        session()->flash('success', 'Profesor creado exitosamente');
        return response()->json(['message' => 'Profesor creado exitosamente']);
    }

    public function edit($id)
    {
        $profesor = Profesor::findOrFail($id);
        return view('admin.profesores.edit', compact('profesor'));
    }

    public function update(Request $request, $id)
    {
        $profesor = Profesor::findOrFail($id);
        
        $validated = $request->validate([
            'ci' => 'required|unique:profesores,ci,' . $id,
            'nombres' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:40',
            'apellido_materno' => 'required|string|max:40',
            'celular' => 'nullable|string|max:20',
            'email' => 'required|email|unique:profesores,email,' . $id,
            'direccion' => 'nullable|string',
            'especialidad' => 'nullable|string|max:100',
            'fecha_ingreso' => 'required|date',
        ]);

        $profesor->update($validated);
        
        session()->flash('success', 'Profesor actualizado exitosamente');
        return response()->json(['message' => 'Profesor actualizado exitosamente']);
    }

    public function destroy($id)
    {
        $profesor = Profesor::findOrFail($id);
        $profesor->update(['estado' => false]);
        
        // Also deactivate user? Usually not done directly here but good practice implicitly/explicitly
        if ($profesor->usuario) {
            // $profesor->usuario->update(['estado' => false]); // If User has estado
        }

        return redirect()->route('admin.profesores')->with('success', 'Profesor desactivado exitosamente');
    }

    public function activate($id)
    {
        $profesor = Profesor::findOrFail($id);
        $profesor->update(['estado' => true]);

        return redirect()->route('admin.profesores')->with('success', 'Profesor activado exitosamente');
    }

    public function getMaterias($id)
    {
        $profesor = Profesor::findOrFail($id);
        $anioActual = AnioAcademico::where('estado', true)->first();
        
        if (!$anioActual) {
            return '<div class="alert alert-warning">No hay un año académico activo.</div>';
        }
        
        $asignaciones = AsignacionDocente::where('profesor_id', $id)
            ->where('anio_academico_id', $anioActual->id)
            ->where('estado', true)
            ->with(['materia', 'paralelo.curso'])
            ->get();

        $todasMaterias = Materia::where('estado', true)->get();
        $paralelos = Paralelo::where('anio_academico_id', $anioActual->id)->with('curso')->get();

        return view('admin.profesores.materias', compact('profesor', 'asignaciones', 'todasMaterias', 'paralelos'));
    }

    public function removeMateria(Request $request)
    {
        $asignacion = AsignacionDocente::findOrFail($request->id_asignacion);
        $asignacion->update(['estado' => false]);

        return back()->with('success', 'Materia removida exitosamente');
    }

    public function addMateria(Request $request)
    {
        $validated = $request->validate([
            'profesor_id' => 'required|exists:profesores,id',
            'materia_id' => 'required|exists:materias,id',
            'paralelo_id' => 'required|exists:paralelos,id',
        ]);

        $anioActual = AnioAcademico::where('estado', true)->first();
        
        // Generar código de asignación
        $ultimaAsignacion = AsignacionDocente::latest('id')->first();
        $numero = $ultimaAsignacion ? $ultimaAsignacion->id + 1 : 1;
        $codigo = 'ASIG-' . date('Y') . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);

        AsignacionDocente::create([
            'codigo' => $codigo,
            'profesor_id' => $validated['profesor_id'],
            'materia_id' => $validated['materia_id'],
            'paralelo_id' => $validated['paralelo_id'],
            'anio_academico_id' => $anioActual->id,
            'estado' => true,
        ]);

        return back()->with('success', 'Materia asignada exitosamente');
    }
}
