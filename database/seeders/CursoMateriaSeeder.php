<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Curso;
use App\Models\Materia;

class CursoMateriaSeeder extends Seeder
{
    public function run()
    {
        $cursos = Curso::all();
        $materias = Materia::all();

        foreach ($cursos as $curso) {
            foreach ($materias as $materia) {
                // Link all subjects to all courses for simplicity in this demo/refactor
                // In a real scenario, this might be more selective based on level
                DB::table('curso_materia')->insert([
                    'curso_id' => $curso->id,
                    'materia_id' => $materia->id,
                    'horas_semanales' => 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
