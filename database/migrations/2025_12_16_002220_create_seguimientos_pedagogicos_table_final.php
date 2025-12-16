<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seguimientos_pedagogicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->constrained('inscripcion_est')->onDelete('cascade');
            $table->string('titulo')->nullable();
            $table->enum('tipo_incidencia', ['CONDUCTA', 'ACADEMICO', 'ASISTENCIA', 'SALUD', 'FAMILIAR', 'OTRO'])->default('OTRO');
            $table->enum('nivel_gravedad', ['BAJO', 'MEDIO', 'ALTO'])->default('BAJO');
            $table->enum('estado', ['PENDIENTE', 'REVISADO', 'RESUELTO'])->default('PENDIENTE');
            $table->string('participacion')->nullable();
            $table->text('apoyo_brindado')->nullable();
            $table->string('progreso')->nullable();
            $table->text('observacion')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha_registro')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos_pedagogicos');
    }
};
