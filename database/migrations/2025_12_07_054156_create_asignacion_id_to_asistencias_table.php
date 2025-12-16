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
        Schema::table('asistencias', function (Blueprint $table) {
            if (!Schema::hasColumn('asistencias', 'asignacion_id')) {
                $table->foreignId('asignacion_id')->nullable()->after('inscripcion_id')->constrained('asignaciones_docente')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropForeign(['asignacion_id']);
            $table->dropColumn('asignacion_id');
        });
    }
};
