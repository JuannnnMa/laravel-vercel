<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CursoMateria extends Model
{
    protected $table = 'curso_materia';

    protected $fillable = ['curso_id', 'materia_id', 'horas_semanales'];

    public function curso(): BelongsTo { return $this->belongsTo(Curso::class); }
    public function materia(): BelongsTo { return $this->belongsTo(Materia::class); }
}
