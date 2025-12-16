<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $cursos = [
            ['codigo' => '1P', 'nombre' => '1° Primaria', 'descripcion' => 'Primer grado de primaria', 'nivel' => 'Primaria', 'grado' => 1],
            ['codigo' => '2P', 'nombre' => '2° Primaria', 'descripcion' => 'Segundo grado de primaria', 'nivel' => 'Primaria', 'grado' => 2],
            ['codigo' => '3P', 'nombre' => '3° Primaria', 'descripcion' => 'Tercer grado de primaria', 'nivel' => 'Primaria', 'grado' => 3],
            ['codigo' => '4P', 'nombre' => '4° Primaria', 'descripcion' => 'Cuarto grado de primaria', 'nivel' => 'Primaria', 'grado' => 4],
            ['codigo' => '5P', 'nombre' => '5° Primaria', 'descripcion' => 'Quinto grado de primaria', 'nivel' => 'Primaria', 'grado' => 5],
            ['codigo' => '6P', 'nombre' => '6° Primaria', 'descripcion' => 'Sexto grado de primaria', 'nivel' => 'Primaria', 'grado' => 6],
            ['codigo' => '1S', 'nombre' => '1° Secundaria', 'descripcion' => 'Primer grado de secundaria', 'nivel' => 'Secundaria', 'grado' => 1],
            ['codigo' => '2S', 'nombre' => '2° Secundaria', 'descripcion' => 'Segundo grado de secundaria', 'nivel' => 'Secundaria', 'grado' => 2],
        ];

        foreach ($cursos as $curso) {
            Curso::create($curso);
        }
    }
}
