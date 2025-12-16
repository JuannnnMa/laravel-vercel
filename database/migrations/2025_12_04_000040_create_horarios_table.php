<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignacion_id')->constrained('asignaciones_docente')->onDelete('cascade');
            $table->string('dia_semana', 20); // lunes, martes, miércoles, jueves, viernes, sábado
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
            
            $table->index(['asignacion_id', 'dia_semana']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
