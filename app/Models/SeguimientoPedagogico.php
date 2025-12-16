<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeguimientoPedagogico extends Model
{
    use SoftDeletes;

    protected $table = 'seguimientos_pedagogicos';

    protected $fillable = [
        'inscripcion_id',
        'titulo',
        'tipo_incidencia',
        'nivel_gravedad',
        'estado',
        'participacion',
        'apoyo_brindado',
        'progreso',
        'observacion',
        'registrado_por',
        'fecha_registro'
    ];

    protected $casts = [
        'fecha_registro' => 'date'
    ];

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(InscripcionEst::class, 'inscripcion_id');
    }

    public function profesor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
