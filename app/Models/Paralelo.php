<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paralelo extends Model
{
    use SoftDeletes;

    protected $table = 'paralelos';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'curso_id', 'anio_academico_id',
        'cupo_maximo', 'inscritos', 'aula', 'turno', 'estado', 'asesor_id'
    ];

    protected $casts = ['estado' => 'boolean'];

    public function curso(): BelongsTo { return $this->belongsTo(Curso::class); }
    public function anioAcademico(): BelongsTo { return $this->belongsTo(AnioAcademico::class); }
    public function inscripciones(): HasMany { return $this->hasMany(InscripcionEst::class); }
    public function asignaciones(): HasMany { return $this->hasMany(AsignacionDocente::class); }
    public function asesor(): BelongsTo { return $this->belongsTo(Profesor::class, 'asesor_id'); }
}
