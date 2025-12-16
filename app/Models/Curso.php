<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;

    protected $table = 'cursos';

    protected $fillable = ['codigo', 'nombre', 'descripcion', 'nivel', 'grado', 'estado'];

    protected $casts = ['estado' => 'boolean'];

    public function paralelos(): HasMany { return $this->hasMany(Paralelo::class); }
    public function materias() {
        return $this->belongsToMany(Materia::class, 'curso_materia')->withPivot('horas_semanales')->withTimestamps();
    }
}
