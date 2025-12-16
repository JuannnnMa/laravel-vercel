<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('inscripcion_id')->constrained('inscripcion_est')->onDelete('cascade');
            $table->string('trimestre', 2); // 1, 2, 3
            
            // Notas por dimensión (Ya no hay items individuales)
            $table->decimal('nota_ser', 5, 2)->default(0);      // 10 pts
            $table->decimal('nota_saber', 5, 2)->default(0);    // 35 pts
            $table->decimal('nota_hacer', 5, 2)->default(0);    // 35 pts
            $table->decimal('nota_decidir', 5, 2)->default(0);  // 10 pts
            
            // Autoevaluaciones
            $table->decimal('autoevaluacion', 5, 2)->default(0); // 10 pts (Promedio de Ser/Decidir autoevaluados)
            
            // Nota final
            $table->integer('nota_final')->default(0); // Entero para el boletín
            $table->string('literal', 50)->nullable();
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
            
            $table->index(['estudiante_id', 'trimestre']);
            $table->unique(['estudiante_id', 'materia_id', 'inscripcion_id', 'trimestre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
