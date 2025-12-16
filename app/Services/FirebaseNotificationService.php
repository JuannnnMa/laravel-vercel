<?php

namespace App\Services;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * Enviar notificación a un usuario específico (Padre/Tutor)
     *
     * @param int $userId ID del usuario padre
     * @param string $title Título
     * @param string $body Mensaje
     * @param array $data Datos adicionales
     */
    public function sendToUser($userId, $title, $body, $data = [])
    {
        try {
            // Buscar el token FCM del usuario (Asumiendo que guardamos el token en la tabla users o una tabla relacionada)
            // Por ahora, asumiremos que el usuario tiene una columna 'fcm_token'
            $user = User::find($userId);

            if (!$user || empty($user->fcm_token)) {
                Log::warning("Intento de enviar notificación a usuario sin token FCM: " . $userId);
                return;
            }

            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $this->messaging->send($message);
            Log::info("Notificación enviada a usuario: " . $userId);

        } catch (\Throwable $e) {
            Log::error("Error enviando notificación FCM: " . $e->getMessage());
        }
    }

    /**
     * Enviar a un tópico (ej: todos los padres de un curso)
     */
    public function sendToTopic($topic, $title, $body)
    {
        try {
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification(Notification::create($title, $body));

            $this->messaging->send($message);
        } catch (\Throwable $e) {
            Log::error("Error enviando notificación a tópico $topic: " . $e->getMessage());
        }
    }
}
