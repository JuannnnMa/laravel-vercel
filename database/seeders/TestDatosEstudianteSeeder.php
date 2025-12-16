<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use App\Models\InscripcionEst;
use App\Models\Paralelo;
use App\Models\AnioAcademico;
use App\Models\Nota;
use App\Models\Asistencia;
use App\Models\Materia;
use Illuminate\Database\Seeder;

class TestDatosEstudianteSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el primer estudiante (Ana María García)
        $estudiante = Estudiante::first();
        
        if (!$estudiante) {
            echo "ERROR: No hay estudiantes\n";
            return;
        }
        
        // Obtener año académico actual
        $anioAcademico = AnioAcademico::where('estado', 1)->first();
        
        if (!$anioAcademico) {
            echo "ERROR: No hay año académico activo\n";
            return;
        }
        
        // Obtener un paralelo
        $paralelo = Paralelo::first();
        
        if (!$paralelo) {
            echo "ERROR: No hay paralelos\n";
            return;
        }
        
        // Verificar si ya tiene inscripción
        $inscripcion = InscripcionEst::where('estudiante_id', $estudiante->id)
            ->where('anio_academico_id', $anioAcademico->id)
            ->first();
            
        if (!$inscripcion) {
            // Crear inscripción
            $inscripcion = InscripcionEst::create([
                'codigo' => 'INS-' . str_pad($estudiante->id, 6, '0', STR_PAD_LEFT),
                'estudiante_id' => $estudiante->id,
                'paralelo_id' => $paralelo->id,
                'anio_academico_id' => $anioAcademico->id,
                'fecha_inscripcion' => now(),
                'estado' => 'activo',
                'observaciones' => 'Inscripción de prueba',
            ]);
            
            echo "✅ Inscripción creada\n";
        } else {
            echo "ℹ️ Inscripción ya existe\n";
        }
        
        // Obtener materias
        $materias = Materia::where('estado', 1)->take(5)->get();
        
        if ($materias->isEmpty()) {
            echo "ERROR: No hay materias\n";
            return;
        }
        
        // Bimestres (solo números: 1, 2, 3, 4)
        $bimestres = ['1', '2', '3', '4'];
        
        // Crear notas para cada materia y bimestre
        $notasCreadas = 0;
        foreach ($materias as $materia) {
            foreach ($bimestres as $bimestre) {
                $notaExiste = Nota::where('inscripcion_id', $inscripcion->id)
                    ->where('materia_id', $materia->id)
                    ->where('bimestre', $bimestre)
                    ->exists();
                    
                if (!$notaExiste) {
                    $notaFinal = rand(60, 100);
                    Nota::create([
                        'estudiante_id' => $estudiante->id,
                        'inscripcion_id' => $inscripcion->id,
                        'materia_id' => $materia->id,
                        'bimestre' => $bimestre,
                        'promedio_ser' => rand(60, 100),
                        'promedio_saber' => rand(60, 100),
                        'promedio_hacer' => rand(60, 100),
                        'promedio_decidir' => rand(60, 100),
                        'nota_final' => $notaFinal,
                        'literal' => $this->getNotaLiteral($notaFinal),
                        'observaciones' => 'Nota de prueba',
                    ]);
                    $notasCreadas++;
                }
            }
        }
        
        echo "✅ Notas creadas: $notasCreadas\n";
        
        // Crear asistencias
        $asistenciasCreadas = 0;
        $fechas = [
            now()->subDays(10),
            now()->subDays(8),
            now()->subDays(5),
            now()->subDays(3),
            now()->subDays(1),
        ];
        
        // Obtener un usuario para registrado_por (el primer admin)
        $admin = \App\Models\User::whereHas('role', function($q) {
            $q->where('nombre', 'administrador');
        })->first();
        
        if (!$admin) {
            $admin = \App\Models\User::first(); // Fallback al primer usuario
        }
        
        foreach ($fechas as $fecha) {
            $asistenciaExiste = Asistencia::where('inscripcion_id', $inscripcion->id)
                ->where('estudiante_id', $estudiante->id)
                ->where('fecha', $fecha->format('Y-m-d'))
                ->exists();
                
            if (!$asistenciaExiste) {
                Asistencia::create([
                    'estudiante_id' => $estudiante->id,
                    'inscripcion_id' => $inscripcion->id,
                    'fecha' => $fecha,
                    'estado' => ['presente', 'presente', 'presente', 'ausente', 'tardanza'][rand(0, 4)],
                    'observacion' => null,
                    'registrado_por' => $admin->id,
                ]);
                $asistenciasCreadas++;
            }
        }
        
        echo "✅ Asistencias creadas: $asistenciasCreadas\n";
        
        echo "\n=== RESUMEN ===\n";
        echo "Estudiante: {$estudiante->nombres} {$estudiante->apellido_paterno}\n";
        echo "Paralelo: {$paralelo->nombre}\n";
        echo "Año Académico: {$anioAcademico->anio}\n";
        echo "Materias: {$materias->count()}\n";
        echo "Bimestres: " . count($bimestres) . "\n";
        echo "===============\n";
    }
    
    private function getNotaLiteral($nota)
    {
        if ($nota >= 91) return 'AD'; // Aprobado Destacado
        if ($nota >= 71) return 'AP'; // Aprobado
        if ($nota >= 51) return 'RP'; // Reprobado
        return 'RD'; // Reprobado Deficiente
    }
}

