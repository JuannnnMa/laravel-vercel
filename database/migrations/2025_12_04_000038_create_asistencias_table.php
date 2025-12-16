<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('inscripcion_id')->constrained('inscripcion_est')->onDelete('cascade');
            $table->date('fecha');
            $table->string('estado', 20)->default('presente'); // presente, ausente, tardanza, justificado
            $table->text('observacion')->nullable();
            $table->foreignId('registrado_por')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['estudiante_id', 'fecha']);
            $table->unique(['estudiante_id', 'inscripcion_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
