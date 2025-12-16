<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paralelos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 50); // A, B, C
            $table->string('descripcion', 200)->nullable();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->foreignId('anio_academico_id')->constrained('anios_academicos')->onDelete('cascade');
            $table->integer('cupo_maximo')->default(30);
            $table->integer('inscritos')->default(0);
            $table->string('aula', 40)->nullable();
            $table->string('turno', 20)->default('mañana'); // mañana, tarde, noche
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('codigo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paralelos');
    }
};
