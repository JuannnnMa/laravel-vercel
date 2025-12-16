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
        Schema::table('paralelos', function (Blueprint $table) {
            if (!Schema::hasColumn('paralelos', 'asesor_id')) {
                $table->foreignId('asesor_id')->nullable()->constrained('profesores')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paralelos', function (Blueprint $table) {
            if (Schema::hasColumn('paralelos', 'asesor_id')) {
                $table->dropForeign(['asesor_id']);
                $table->dropColumn('asesor_id');
            }
        });
    }
};
