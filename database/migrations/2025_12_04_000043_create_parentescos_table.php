<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parentescos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('tutores')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->string('tipo_parentesco', 50); // padre, madre, tutor legal, etc.
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
            
            $table->index(['tutor_id', 'estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parentescos');
    }
};
