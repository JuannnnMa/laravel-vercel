<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AsignacionDocente extends Model
{
    protected $table = 'asignaciones_docente';

    protected $fillable = [
        'codigo', 'profesor_id', 'materia_id', 'paralelo_id',
        'anio_academico_id', 'estado',
    ];

    protected $casts = ['estado' => 'boolean'];

    public function profesor(): BelongsTo { return $this->belongsTo(Profesor::class); }
    public function materia(): BelongsTo { return $this->belongsTo(Materia::class); }
    public function paralelo(): BelongsTo { return $this->belongsTo(Paralelo::class); }
    public function anioAcademico(): BelongsTo { return $this->belongsTo(AnioAcademico::class); }
    public function horarios(): HasMany { return $this->hasMany(Horario::class, 'asignacion_id'); }
    public function ponderaciones(): HasMany { return $this->hasMany(Ponderacion::class, 'asignacion_id'); }
}
