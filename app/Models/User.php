<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'email',
        'password',
        'role_id',
        'email_verified_at',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // Relaciones directas en lugar de polimórfica
    public function profesor()
    {
        return $this->hasOne(Profesor::class);
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }

    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->nombre === $roleName;
    }

    /**
     * Verificar si el usuario es tutor
     */
    public function esTutor(): bool
    {
        return $this->hasRole('tutor');
    }

    /**
     * Verificar si el usuario es profesor
     */
    public function esProfesor(): bool
    {
        return $this->hasRole('profesor');
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdministrador(): bool
    {
        return $this->hasRole('administrador');
    }

    /**
     * Obtener nombres completos del usuario dependiendo de su rol
     */
    public function getNombresAttribute(): ?string
    {
        if ($this->esProfesor()) {
            return $this->profesor->nombres ?? null;
        }
        if ($this->hasRole('estudiante')) {
            return $this->estudiante->nombres ?? null;
        }
        if ($this->esTutor()) {
            return $this->tutor->nombres ?? null;
        }
        return 'Usuario';
    }

    /**
     * Obtener apellidos completos del usuario dependiendo de su rol
     */
    public function getApellidosAttribute(): ?string
    {
        $persona = null;
        
        if ($this->esProfesor()) {
            $persona = $this->profesor;
        } elseif ($this->hasRole('estudiante')) {
            $persona = $this->estudiante;
        } elseif ($this->esTutor()) {
            $persona = $this->tutor;
        }

        if ($persona) {
            $apellidoPaterno = $persona->apellido_paterno ?? '';
            $apellidoMaterno = $persona->apellido_materno ?? '';
            return trim("$apellidoPaterno $apellidoMaterno");
        }
        
        return '';
    }
}
