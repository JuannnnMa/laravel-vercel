<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profesor extends Model
{
    use SoftDeletes;

    protected $table = 'profesores';

    protected $fillable = [
        'ci', 'codigo_profesor', 'item', 'apellido_paterno', 'apellido_materno',
        'nombres', 'celular', 'email', 'direccion', 'especialidad',
        'fecha_ingreso', 'foto_url', 'user_id', 'estado',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'estado' => 'boolean',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function asignaciones(): HasMany { return $this->hasMany(AsignacionDocente::class); }
    public function paralelosAsesorados(): HasMany { return $this->hasMany(Paralelo::class, 'asesor_id'); }
}
