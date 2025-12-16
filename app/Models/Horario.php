<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = ['asignacion_id', 'dia_semana', 'hora_inicio', 'hora_fin'];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    public function asignacion(): BelongsTo { return $this->belongsTo(AsignacionDocente::class, 'asignacion_id'); }
}
