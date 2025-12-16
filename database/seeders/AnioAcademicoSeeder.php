<?php

namespace Database\Seeders;

use App\Models\AnioAcademico;
use Illuminate\Database\Seeder;

class AnioAcademicoSeeder extends Seeder
{
    public function run(): void
    {
        AnioAcademico::create([
            'nombre' => '2024',
            'fecha_inicio' => '2024-03-01',
            'fecha_fin' => '2024-12-20',
            'estado' => true, // Activo
        ]);

        AnioAcademico::create([
            'nombre' => '2025',
            'fecha_inicio' => '2025-03-01',
            'fecha_fin' => '2025-12-20',
            'estado' => false,
        ]);
    }
}
