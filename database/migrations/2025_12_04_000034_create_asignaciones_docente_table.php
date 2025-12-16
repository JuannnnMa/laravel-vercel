<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones_docente', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->foreignId('profesor_id')->constrained('profesores')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('paralelo_id')->constrained('paralelos')->onDelete('cascade');
            $table->foreignId('anio_academico_id')->constrained('anios_academicos')->onDelete('cascade');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            
            $table->index('codigo');
            $table->index(['profesor_id', 'anio_academico_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones_docente');
    }
};
