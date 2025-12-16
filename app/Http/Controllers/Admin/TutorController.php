<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Role;
use App\Models\Parentesco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TutorController extends Controller
{
    public function index()
    {
        $tutores = Tutor::withCount('estudiantes')->orderBy('apellido_paterno')->get();
        return view('admin.tutores.index', compact('tutores'));
    }

    public function create()
    {
        return view('admin.tutores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:40',
            'apellido_materno' => 'required|string|max:40',
            'ci' => 'required|string|max:20|unique:tutores,ci',
            'celular' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:tutores,email',
            'direccion' => 'nullable|string|max:200',
            'ocupacion' => 'nullable|string|max:80',
            'password' => 'nullable|string|min:6', // Optional if email is provided
        ]);

        DB::transaction(function () use ($validated, $request) {
            // Crear usuario si tiene email
            $userId = null;
            if (!empty($validated['email'])) {
                $request->validate(['password' => 'required|string|min:6']); // Required if email exists
                
                $tutorRole = Role::where('nombre', 'tutor')->first();
                $user = User::create([
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']), // Custom password
                    'role_id' => $tutorRole->id,
                ]);
                $userId = $user->id;
            }

            $validated['user_id'] = $userId;
            $tutor = Tutor::create($validated);

            if ($userId) {
                $user = User::find($userId);
                $user->update([
                    'userable_type' => Tutor::class,
                    'userable_id' => $tutor->id,
                ]);
            }
        });

        session()->flash('success', 'Tutor creado exitosamente');
        return response()->json(['message' => 'Tutor creado exitosamente']);
    }

    public function edit($id)
    {
        $tutor = Tutor::findOrFail($id);
        return view('admin.tutores.edit', compact('tutor'));
    }

    public function update(Request $request, $id)
    {
        $tutor = Tutor::findOrFail($id);
        
        $validated = $request->validate([
            'nombres' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:40',
            'apellido_materno' => 'required|string|max:40',
            'ci' => 'required|string|max:20|unique:tutores,ci,' . $id,
            'celular' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:tutores,email,' . $id,
            'direccion' => 'nullable|string|max:200',
            'ocupacion' => 'nullable|string|max:80',
        ]);

        $tutor->update($validated);

        session()->flash('success', 'Tutor actualizado exitosamente');
        return response()->json(['message' => 'Tutor actualizado exitosamente']);
    }

    public function destroy($id)
    {
        $tutor = Tutor::findOrFail($id);
        $tutor->delete();

        return redirect()->route('admin.tutores')->with('success', 'Tutor eliminado exitosamente');
    }

    public function getEstudiantes($id)
    {
        $tutor = Tutor::with(['estudiantes' => function($query) {
            $query->with(['inscripciones' => function($q) {
                $q->where('estado', 'activo')->with('paralelo.curso');
            }]);
        }])->findOrFail($id);

        return view('admin.tutores.estudiantes', compact('tutor'));
    }
}
