<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comunicado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComunicadoController extends Controller
{
    public function index()
    {
        $comunicados = Comunicado::with('creador')->latest()->get();
        return view('admin.comunicados.index', compact('comunicados'));
    }

    public function create()
    {
        return view('admin.comunicados.create');
    }

    public function edit($id)
    {
        $comunicado = Comunicado::findOrFail($id);
        return view('admin.comunicados.edit', compact('comunicado'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:191',
            'mensaje' => 'required|string',
            'tipo' => 'required|string|max:50',
            'destinatarios' => 'required|string|max:50',
            'fecha_evento' => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::id();

        $comunicado = Comunicado::create($validated);

        // Notificar a usuarios vía APP
        try {
            // $notificationService = app(\App\Services\FirebaseNotificationService::class);
            $topic = 'todos';
            
            if ($validated['destinatarios'] === 'docentes') $topic = 'docentes';
            elseif ($validated['destinatarios'] === 'padres') $topic = 'padres';
            elseif ($validated['destinatarios'] === 'estudiantes') $topic = 'estudiantes';

            \Illuminate\Support\Facades\Log::info("Enviando notificacion a topico: $topic -> " . $validated['titulo']);
            // Si tuvieramos el servicio configurado sería:
            // $notificationService->sendToTopic($topic, $validated['titulo'], substr($validated['mensaje'], 0, 100));
             
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error enviando notificacion: " . $e->getMessage());
        }

        
        session()->flash('success', 'Comunicado publicado correctamente');
        return response()->json(['message' => 'Comunicado publicado correctamente']);
    }

    public function update(Request $request, $id)
    {
        $comunicado = Comunicado::findOrFail($id);
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:191',
            'mensaje' => 'required|string',
            'tipo' => 'required|string|max:50',
            'destinatarios' => 'required|string|max:50',
            'fecha_evento' => 'nullable|date',
        ]);

        $comunicado->update($validated);

        session()->flash('success', 'Comunicado actualizado correctamente');
        return response()->json(['message' => 'Comunicado actualizado correctamente']);
    }

    public function destroy($id)
    {
        $comunicado = Comunicado::findOrFail($id);
        $comunicado->delete();
        return back()->with('success', 'Comunicado eliminado');
    }
}
