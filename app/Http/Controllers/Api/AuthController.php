<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tutor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Buscar usuario en tabla users con su rol
        $user = \App\Models\User::with('role')->where('email', $request->email)->first();

        // 2. Verificar password
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Las credenciales proporcionadas son incorrectas.',
            ], 422);
        }

        // 3. Verificar que tenga rol de tutor
        if (!$user->esTutor()) {
            return response()->json([
                'success' => false,
                'message' => 'Este usuario no tiene permisos de tutor.',
            ], 403);
        }

        // 4. Buscar el perfil de Tutor asociado
        $tutor = Tutor::where('user_id', $user->id)->first();
        
        if (!$tutor) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el perfil de tutor asociado a este usuario.',
            ], 404);
        }

        // Crear token
        $token = $user->createToken('movil-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'tutor' => [
                'id_tutor' => $tutor->id,
                'ci' => $tutor->ci,
                'nombres' => $tutor->nombres,
                'apellido_paterno' => $tutor->apellido_paterno,
                'apellido_materno' => $tutor->apellido_materno,
                'email' => $tutor->email,
                'celular' => $tutor->celular,
                'ocupacion' => $tutor->ocupacion,
            ],
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'nombres' => $tutor->nombres,
                'apellidos' => trim($tutor->apellido_paterno . ' ' . $tutor->apellido_materno),
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente',
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Token FCM actualizado correctamente',
        ]);
    }
}
