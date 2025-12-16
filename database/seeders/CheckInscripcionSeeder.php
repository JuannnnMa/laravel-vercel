<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use App\Models\InscripcionEst;
use Illuminate\Database\Seeder;

class CheckInscripcionSeeder extends Seeder
{
    public function run(): void
    {
        $estudiante = Estudiante::find(1);

        if ($estudiante) {
            echo "Estudiante encontrado: {$estudiante->nombres} {$estudiante->apellido_paterno}\n";
            
            $inscripciones = $estudiante->inscripciones()->get();
            echo "Total inscripciones: {$inscripciones->count()}\n";
            
            foreach ($inscripciones as $insc) {
                echo "  - ID: {$insc->id}, Estado: {$insc->estado}, Fecha: {$insc->fecha_inscripcion}\n";
            }
            
            $inscripcionActiva = $estudiante->inscripciones()
                ->where('estado', 1)
                ->latest('fecha_inscripcion')
                ->first();
                
            if ($inscripcionActiva) {
                echo "\nInscripción activa encontrada:\n";
                echo "  ID: {$inscripcionActiva->id}\n";
                echo "  Estado: {$inscripcionActiva->estado}\n";
                
                $notas = $inscripcionActiva->notas()->count();
                $asistencias = $inscripcionActiva->asistencias()->count();
                
                echo "  Notas: {$notas}\n";
                echo "  Asistencias: {$asistencias}\n";
            } else {
                echo "\n NO hay inscripción activa\n";
            }
        } else {
            echo " Estudiante NO encontrado\n";
        }
    }
}
