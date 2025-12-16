<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nota extends Model
{
    protected $table = 'notas';

    protected $fillable = [
        'estudiante_id', 'materia_id', 'inscripcion_id', 'trimestre',
        'nota_ser', 'nota_saber', 'nota_hacer', 'nota_decidir',
        'autoevaluacion', 'nota_final', 'literal', 'observaciones',
    ];

    protected $casts = [
        'nota_ser' => 'decimal:2',
        'nota_saber' => 'decimal:2',
        'nota_hacer' => 'decimal:2',
        'nota_decidir' => 'decimal:2',
        'autoevaluacion' => 'decimal:2',
        'nota_final' => 'integer',
    ];

    public function estudiante(): BelongsTo { return $this->belongsTo(Estudiante::class); }
    public function materia(): BelongsTo { return $this->belongsTo(Materia::class); }
    public function inscripcion(): BelongsTo { return $this->belongsTo(InscripcionEst::class, 'inscripcion_id'); }
}
