<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        if (!$user->role_id || !$user->role) {
            auth()->logout();
            return redirect('/login')->with('error', 'Usuario sin rol asignado.');
        }

        $userRole = $user->role->nombre;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'No tienes permiso para acceder a este recurso.');
    }
}
