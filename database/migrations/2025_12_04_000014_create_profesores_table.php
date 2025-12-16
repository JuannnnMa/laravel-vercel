<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profesores', function (Blueprint $table) {
            $table->id();
            $table->string('ci', 20)->unique();
            $table->string('codigo_profesor', 30)->unique();
            $table->string('item', 30)->nullable();
            $table->string('apellido_paterno', 40);
            $table->string('apellido_materno', 40);
            $table->string('nombres', 50);
            $table->string('celular', 20)->nullable();
            $table->string('email', 50)->unique();
            $table->string('direccion', 200)->nullable();
            $table->string('especialidad', 100)->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->string('foto_url')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('ci');
            $table->index('codigo_profesor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};
