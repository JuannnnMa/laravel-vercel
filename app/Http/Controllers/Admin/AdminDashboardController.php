<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profesor;
use App\Models\Estudiante;
use App\Models\Tutor;

class AdminDashboardController extends Controller
{
   

    public function index()
    {
        $user = auth()->user();

        // Verificar rol directamente
        if (!$user || $user->role->nombre !== 'administrador') {
            abort(403, 'No tienes permiso para acceder a este recurso.');
        }

        $totalProfesores = Profesor::count();
        $totalEstudiantes = Estudiante::count();
        $totalTutores = Tutor::count();
        $totalUsuarios = User::count();

        return view('admin.dashboard', compact(
            'totalProfesores',
            'totalEstudiantes',
            'totalTutores',
            'totalUsuarios'
        ));
    }

    public function profesores()
    {
        $user = auth()->user();
        if (!$user || $user->role->nombre !== 'administrador') {
            abort(403);
        }

        $profesores = User::whereHas('role', function ($query) {
            $query->where('nombre', 'profesor');
        })->paginate(15);

        return view('admin.profesores.index', compact('profesores'));
    }

    public function estudiantes()
    {
        $user = auth()->user();
        if (!$user || $user->role->nombre !== 'administrador') {
            abort(403);
        }

        $estudiantes = User::whereHas('role', function ($query) {
            $query->where('nombre', 'estudiante');
        })->paginate(15);

        return view('admin.estudiantes.index', compact('estudiantes'));
    }

    public function tutores()
    {
        $user = auth()->user();
        if (!$user || $user->role->nombre !== 'administrador') {
            abort(403);
        }

        $tutores = User::whereHas('role', function ($query) {
            $query->where('nombre', 'tutor');
        })->paginate(15);

        return view('admin.tutores.index', compact('tutores'));
    }

    public function cambiarGestion(\Illuminate\Http\Request $request)
    {
        $anioId = $request->input('anio_id');
        
        if ($anioId) {
            session(['anio_seleccionado_id' => $anioId]);
        } else {
            session()->forget('anio_seleccionado_id');
        }
        
        return back()->with('success', 'Gestión académica cambiada correctamente');
    }
}
