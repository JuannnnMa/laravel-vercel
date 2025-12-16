<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comunicado;
use Illuminate\Support\Facades\Auth;

class ComunicadoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Usar el scope paraUsuario que creamos en el Modelo
        $comunicados = Comunicado::paraUsuario($user)->latest()->get();
        
        return view('docente.comunicados.index', compact('comunicados'));
    }

    public function show($id)
    {
        $comunicado = Comunicado::findOrFail($id);
        return view('docente.comunicados.show', compact('comunicado'));
    }
}
