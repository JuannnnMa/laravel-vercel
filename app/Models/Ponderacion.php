<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ponderacion extends Model
{
    protected $table = 'ponderaciones';

    protected $fillable = [
        'asignacion_id', 'bimestre',
        'item_1_descripcion', 'item_2_descripcion', 'item_3_descripcion', 'item_4_descripcion',
        'item_5_descripcion', 'item_6_descripcion', 'item_7_descripcion', 'item_8_descripcion',
        'item_9_descripcion', 'item_10_descripcion', 'item_11_descripcion', 'item_12_descripcion',
        'item_13_descripcion', 'item_14_descripcion', 'item_15_descripcion', 'item_16_descripcion',
    ];

    public function asignacion(): BelongsTo { return $this->belongsTo(AsignacionDocente::class, 'asignacion_id'); }
}
