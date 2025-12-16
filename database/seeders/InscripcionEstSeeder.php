<?php

namespace Database\Seeders;

use App\Models\InscripcionEst;
use App\Models\Estudiante;
use App\Models\Paralelo;
use App\Models\AnioAcademico;
use Illuminate\Database\Seeder;

class InscripcionEstSeeder extends Seeder
{
    public function run(): void
    {
        $anio = AnioAcademico::where('estado', true)->first();
        $estudiantes = Estudiante::all();
        $paralelo = Paralelo::where('codigo', '1P-A')->first();

        $contador = 1;
        foreach ($estudiantes as $estudiante) {
            InscripcionEst::create([
                'codigo' => 'INS-2024-' . str_pad($contador, 4, '0', STR_PAD_LEFT),
                'estudiante_id' => $estudiante->id,
                'paralelo_id' => $paralelo->id,
                'anio_academico_id' => $anio->id,
                'fecha_inscripcion' => '2024-03-01',
                'estado' => 'activo',
            ]);
            $contador++;
        }

        // Actualizar inscritos en paralelo
        $paralelo->update(['inscritos' => $estudiantes->count()]);
    }
}
