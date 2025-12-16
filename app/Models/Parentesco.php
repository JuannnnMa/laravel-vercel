<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parentesco extends Model
{
    protected $table = 'parentescos';

    protected $fillable = ['tutor_id', 'estudiante_id', 'tipo_parentesco', 'es_principal'];

    protected $casts = ['es_principal' => 'boolean'];

    public function tutor(): BelongsTo { return $this->belongsTo(Tutor::class); }
    public function estudiante(): BelongsTo { return $this->belongsTo(Estudiante::class); }
}
