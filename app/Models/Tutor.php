<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use SoftDeletes;

    protected $table = 'tutores';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ci', 'apellido_paterno', 'apellido_materno', 'nombres',
        'direccion', 'celular', 'email', 'ocupacion', 'user_id', 'estado',
    ];

    protected $casts = ['estado' => 'boolean'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function parentescos(): HasMany { return $this->hasMany(Parentesco::class); }
    public function estudiantes() {
        return $this->belongsToMany(Estudiante::class, 'parentescos')->withPivot('id', 'tipo_parentesco', 'es_principal')->withTimestamps();
    }
    public function permisos(): HasMany { return $this->hasMany(Permiso::class); }
}
