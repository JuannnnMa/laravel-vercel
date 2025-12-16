<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materia extends Model
{
    use SoftDeletes;

    protected $table = 'materias';

    protected $fillable = ['codigo', 'nombre', 'descripcion', 'area_id', 'estado'];

    protected $casts = ['estado' => 'boolean'];

    public function area(): BelongsTo { return $this->belongsTo(Area::class); }
    public function cursos() {
        return $this->belongsToMany(Curso::class, 'curso_materia')->withPivot('horas_semanales')->withTimestamps();
    }
    public function asignaciones(): HasMany { return $this->hasMany(AsignacionDocente::class); }
    public function notas(): HasMany { return $this->hasMany(Nota::class); }
}
