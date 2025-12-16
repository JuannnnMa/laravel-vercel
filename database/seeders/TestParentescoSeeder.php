<?php

namespace Database\Seeders;

use App\Models\Parentesco;
use App\Models\Tutor;
use App\Models\Estudiante;
use Illuminate\Database\Seeder;

class TestParentescoSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar el tutor de prueba
        $tutorTest = Tutor::where('email', 'test@test.com')->first();
        
        if (!$tutorTest) {
            echo "ERROR: Tutor de prueba no encontrado\n";
            return;
        }
        
        // Buscar el primer estudiante
        $estudiante = Estudiante::first();
        
        if (!$estudiante) {
            echo "ERROR: No hay estudiantes en la base de datos\n";
            return;
        }
        
        // Verificar si ya existe la relación
        $existente = Parentesco::where('tutor_id', $tutorTest->id)
            ->where('estudiante_id', $estudiante->id)
            ->exists();
            
        if ($existente) {
            echo "La relación ya existe\n";
            return;
        }
        
        // Crear la relación
        Parentesco::create([
            'tutor_id' => $tutorTest->id,
            'estudiante_id' => $estudiante->id,
            'tipo_parentesco' => 'padre',
            'es_principal' => true,
        ]);
        
        echo "✅ Relación creada exitosamente\n";
        echo "Tutor: {$tutorTest->nombres} {$tutorTest->apellido_paterno}\n";
        echo "Estudiante: {$estudiante->nombres} {$estudiante->apellido_paterno}\n";
    }
}
