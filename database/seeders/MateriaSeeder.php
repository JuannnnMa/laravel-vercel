<?php

namespace Database\Seeders;

use App\Models\Materia;
use App\Models\Area;
use Illuminate\Database\Seeder;

class MateriaSeeder extends Seeder
{
    public function run(): void
    {
        $com = Area::where('codigo', 'COM')->first();
        $mat = Area::where('codigo', 'MAT')->first();
        $cyt = Area::where('codigo', 'CYT')->first();
        $cys = Area::where('codigo', 'CYS')->first();
        $art = Area::where('codigo', 'ART')->first();
        $edf = Area::where('codigo', 'EDF')->first();
        $ing = Area::where('codigo', 'ING')->first();

        $materias = [
            ['codigo' => 'COM-01', 'nombre' => 'Comunicación', 'area_id' => $com->id],
            ['codigo' => 'MAT-01', 'nombre' => 'Matemática', 'area_id' => $mat->id],
            ['codigo' => 'CYT-01', 'nombre' => 'Ciencia y Tecnología', 'area_id' => $cyt->id],
            ['codigo' => 'CYS-01', 'nombre' => 'Personal Social', 'area_id' => $cys->id],
            ['codigo' => 'ART-01', 'nombre' => 'Arte y Cultura', 'area_id' => $art->id],
            ['codigo' => 'EDF-01', 'nombre' => 'Educación Física', 'area_id' => $edf->id],
            ['codigo' => 'ING-01', 'nombre' => 'Inglés', 'area_id' => $ing->id],
        ];

        foreach ($materias as $materia) {
            Materia::create($materia);
        }
    }
}
