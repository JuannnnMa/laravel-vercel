<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comunicado extends Model
{
    use SoftDeletes;

    protected $table = 'comunicados';

    protected $fillable = [
        'titulo',
        'mensaje',
        'tipo', // aviso, reunion, evento, urgente
        'destinatarios', // todos, docentes, padres, estudiantes
        'fecha_evento',
        'user_id',
        'estado'
    ];

    protected $casts = [
        'fecha_evento' => 'datetime',
        'estado' => 'boolean'
    ];

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope para filtrar por destinatario
    public function scopeParaUsuario($query, User $user)
    {
        $role = $user->role->nombre;
        
        return $query->where(function($q) use ($role) {
            $q->where('destinatarios', 'todos');
            
            if ($role === 'docente' || $role === 'profesor') {
                $q->orWhere('destinatarios', 'docentes');
            } elseif ($role === 'tutor' || $role === 'padre') {
                $q->orWhere('destinatarios', 'padres');
            } elseif ($role === 'estudiante') {
                $q->orWhere('destinatarios', 'estudiantes');
            }
        });
    }
}
