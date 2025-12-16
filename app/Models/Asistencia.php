<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $fillable = [
        'estudiante_id', 'inscripcion_id', 'asignacion_id', 'fecha', 'estado', 'observacion', 'registrado_por',
    ];

    protected $casts = ['fecha' => 'date'];

    public function estudiante(): BelongsTo { return $this->belongsTo(Estudiante::class); }
    public function inscripcion(): BelongsTo { return $this->belongsTo(InscripcionEst::class, 'inscripcion_id'); }
    public function asignacion(): BelongsTo { return $this->belongsTo(AsignacionDocente::class, 'asignacion_id'); }
    public function registradoPor(): BelongsTo { return $this->belongsTo(User::class, 'registrado_por'); }
}
