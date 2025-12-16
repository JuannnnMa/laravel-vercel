<?php

namespace Database\Seeders;

use App\Models\Paralelo;
use App\Models\Curso;
use App\Models\AnioAcademico;
use Illuminate\Database\Seeder;

class ParaleloSeeder extends Seeder
{
    public function run(): void
    {
        $anio = AnioAcademico::where('estado', true)->first();
        $cursos = Curso::all();

        foreach ($cursos as $curso) {
            $paralelos = ['A', 'B'];
            foreach ($paralelos as $nombre) {
                Paralelo::create([
                    'codigo' => $curso->codigo . '-' . $nombre,
                    'nombre' => $nombre,
                    'descripcion' => $curso->nombre . ' - Paralelo ' . $nombre,
                    'curso_id' => $curso->id,
                    'anio_academico_id' => $anio->id,
                    'cupo_maximo' => 30,
                    'inscritos' => 0,
                    'aula' => 'Aula ' . $curso->grado . $nombre,
                    'turno' => 'mañana',
                ]);
            }
        }
    }
}
