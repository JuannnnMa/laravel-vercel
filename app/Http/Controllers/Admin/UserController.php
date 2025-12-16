<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // Traer usuarios con sus roles
        // Ocultar admin principal si se desea, por ahora mostramos todos
        $users = User::with('role')->get();
        return view('admin.usuarios.index', compact('users'));
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        session()->flash('success', 'Contraseña actualizada exitosamente');
        return response()->json(['message' => 'Contraseña actualizada exitosamente']);
    }
}
