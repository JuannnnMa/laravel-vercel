<?php

namespace Database\Seeders;

use App\Models\Parentesco;
use App\Models\Tutor;
use App\Models\Estudiante;
use Illuminate\Database\Seeder;

class ParentescoSeeder extends Seeder
{
    public function run(): void
    {
        $tutores = Tutor::all();
        $estudiantes = Estudiante::all();

        // Asignar tutores a estudiantes
        if ($tutores->count() > 0 && $estudiantes->count() > 0) {
            Parentesco::create([
                'tutor_id' => $tutores[0]->id,
                'estudiante_id' => $estudiantes[0]->id,
                'tipo_parentesco' => 'padre',
                'es_principal' => true,
            ]);

            Parentesco::create([
                'tutor_id' => $tutores[1]->id,
                'estudiante_id' => $estudiantes[1]->id,
                'tipo_parentesco' => 'madre',
                'es_principal' => true,
            ]);

            if ($estudiantes->count() > 2) {
                Parentesco::create([
                    'tutor_id' => $tutores[2]->id,
                    'estudiante_id' => $estudiantes[2]->id,
                    'tipo_parentesco' => 'padre',
                    'es_principal' => true,
                ]);
            }
        }
    }
}
