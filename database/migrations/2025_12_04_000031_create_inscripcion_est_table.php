<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripcion_est', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('paralelo_id')->constrained('paralelos')->onDelete('cascade');
            $table->foreignId('anio_academico_id')->constrained('anios_academicos')->onDelete('cascade');
            $table->date('fecha_inscripcion');
            $table->string('estado', 20)->default('activo'); // activo, retirado, trasladado, finalizado
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('codigo');
            $table->index(['estudiante_id', 'anio_academico_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripcion_est');
    }
};
