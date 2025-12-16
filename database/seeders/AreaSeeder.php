<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['codigo' => 'COM', 'nombre' => 'Comunicación', 'descripcion' => 'Área de Comunicación'],
            ['codigo' => 'MAT', 'nombre' => 'Matemática', 'descripcion' => 'Área de Matemática'],
            ['codigo' => 'CYT', 'nombre' => 'Ciencia y Tecnología', 'descripcion' => 'Área de Ciencia y Tecnología'],
            ['codigo' => 'CYS', 'nombre' => 'Ciencias Sociales', 'descripcion' => 'Área de Ciencias Sociales'],
            ['codigo' => 'ART', 'nombre' => 'Arte y Cultura', 'descripcion' => 'Área de Arte y Cultura'],
            ['codigo' => 'EDF', 'nombre' => 'Educación Física', 'descripcion' => 'Área de Educación Física'],
            ['codigo' => 'ING', 'nombre' => 'Inglés', 'descripcion' => 'Área de Inglés'],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
