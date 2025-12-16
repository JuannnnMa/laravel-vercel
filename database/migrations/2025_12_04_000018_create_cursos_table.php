<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 100);
            $table->string('descripcion', 200)->nullable();
            $table->string('nivel', 50); // Primaria, Secundaria
            $table->integer('grado'); // 1-6
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('codigo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
