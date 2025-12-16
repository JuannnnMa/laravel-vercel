<?php

namespace Database\Seeders;

use App\Models\AsignacionDocente;
use App\Models\Profesor;
use App\Models\Materia;
use App\Models\Paralelo;
use App\Models\AnioAcademico;
use Illuminate\Database\Seeder;

class AsignacionDocenteSeeder extends Seeder
{
    public function run(): void
    {
        $anio = AnioAcademico::where('estado', true)->first();
        $profesores = Profesor::all();
        $materias = Materia::take(3)->get();
        $paralelo = Paralelo::where('codigo', '1P-A')->first();

        $contador = 1;
        foreach ($profesores as $index => $profesor) {
            if (isset($materias[$index])) {
                AsignacionDocente::create([
                    'codigo' => 'ASIG-2024-' . str_pad($contador, 4, '0', STR_PAD_LEFT),
                    'profesor_id' => $profesor->id,
                    'materia_id' => $materias[$index]->id,
                    'paralelo_id' => $paralelo->id,
                    'anio_academico_id' => $anio->id,
                    'estado' => true,
                ]);
                $contador++;
            }
        }
    }
}
