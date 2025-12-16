<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estudiante extends Model
{
    use SoftDeletes;

    protected $table = 'estudiantes';

    protected $fillable = [
        'ci', 'rude', 'codigo_estudiante', 'apellido_paterno', 'apellido_materno',
        'nombres', 'fecha_nacimiento', 'genero', 'email', 'direccion',
        'telefono', 'foto_url', 'user_id', 'estado',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'estado' => 'boolean',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function inscripciones(): HasMany { return $this->hasMany(InscripcionEst::class); }
    public function notas(): HasMany { return $this->hasMany(Nota::class); }
    public function asistencias(): HasMany { return $this->hasMany(Asistencia::class); }
    public function parentescos(): HasMany { return $this->hasMany(Parentesco::class); }
    public function tutores() {
        return $this->belongsToMany(Tutor::class, 'parentescos')->withPivot('id', 'tipo_parentesco', 'es_principal')->withTimestamps();
    }
    public function permisos(): HasMany { return $this->hasMany(Permiso::class); }
}
