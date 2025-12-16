<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Docente;

class DocenteAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('docente.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Por ahora, usaremos el email como contraseña para facilitar el acceso
        // En un sistema real, se debería usar Hash::check
        $docente = Docente::where('email', $credentials['email'])->first();

        if ($docente && $docente->ci === $credentials['password']) {
            Auth::guard('docente')->login($docente);
            $request->session()->regenerate();
            return redirect()->intended(route('docente.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('docente')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('docente.login');
    }
}
