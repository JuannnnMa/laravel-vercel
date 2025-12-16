<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'estudiante_id', 'tutor_id', 'fecha_solicitud', 'fecha_permiso',
        'motivo', 'estado', 'aprobado_por', 'observaciones',
    ];

    protected $casts = [
        'fecha_solicitud' => 'date',
        'fecha_permiso' => 'date',
    ];

    public function estudiante(): BelongsTo { return $this->belongsTo(Estudiante::class); }
    public function tutor(): BelongsTo { return $this->belongsTo(Tutor::class); }
    public function aprobadoPor(): BelongsTo { return $this->belongsTo(User::class, 'aprobado_por'); }
}
