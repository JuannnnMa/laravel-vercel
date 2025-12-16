<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnioAcademico extends Model
{
    protected $table = 'anios_academicos';

    protected $fillable = ['nombre', 'fecha_inicio', 'fecha_fin', 'estado'];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'estado' => 'boolean',
    ];

    public function paralelos(): HasMany { return $this->hasMany(Paralelo::class); }
    public function inscripciones(): HasMany { return $this->hasMany(InscripcionEst::class); }
    public function asignaciones(): HasMany { return $this->hasMany(AsignacionDocente::class); }
}
