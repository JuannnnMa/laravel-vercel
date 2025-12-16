<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InscripcionEst extends Model
{
    use SoftDeletes;

    protected $table = 'inscripcion_est';

    protected $fillable = [
        'codigo', 'estudiante_id', 'paralelo_id', 'anio_academico_id',
        'fecha_inscripcion', 'estado', 'observaciones',
    ];

    protected $casts = ['fecha_inscripcion' => 'date'];

    public function estudiante(): BelongsTo { return $this->belongsTo(Estudiante::class); }
    public function paralelo(): BelongsTo { return $this->belongsTo(Paralelo::class); }
    public function anioAcademico(): BelongsTo { return $this->belongsTo(AnioAcademico::class); }
    public function notas(): HasMany { return $this->hasMany(Nota::class, 'inscripcion_id'); }
    public function asistencias(): HasMany { return $this->hasMany(Asistencia::class, 'inscripcion_id'); }
}
