<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $table = 'areas';

    protected $fillable = ['codigo', 'nombre', 'descripcion', 'estado'];

    protected $casts = ['estado' => 'boolean'];

    public function materias(): HasMany { return $this->hasMany(Materia::class); }
}
