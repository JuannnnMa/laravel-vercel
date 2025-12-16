<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EstudianteSeeder extends Seeder
{
    public function run(): void
    {
        $estudianteRole = Role::where('nombre', 'estudiante')->first();
        $contador = 1;

        $estudiantes = [
            ['ci' => '45678901', 'nombres' => 'Ana María', 'apellido_paterno' => 'García', 'apellido_materno' => 'López', 'genero' => 'F', 'fecha_nacimiento' => '2012-05-15'],
            ['ci' => '56789012', 'nombres' => 'Carlos Alberto', 'apellido_paterno' => 'Rodríguez', 'apellido_materno' => 'Pérez', 'genero' => 'M', 'fecha_nacimiento' => '2012-08-20'],
            ['ci' => '67890123', 'nombres' => 'Lucía Fernanda', 'apellido_paterno' => 'Martínez', 'apellido_materno' => 'Sánchez', 'genero' => 'F', 'fecha_nacimiento' => '2013-03-10'],
            ['ci' => '78901234', 'nombres' => 'Diego Alejandro', 'apellido_paterno' => 'López', 'apellido_materno' => 'Torres', 'genero' => 'M', 'fecha_nacimiento' => '2013-11-25'],
            ['ci' => '89012345', 'nombres' => 'Sofía Isabel', 'apellido_paterno' => 'Hernández', 'apellido_materno' => 'Ramírez', 'genero' => 'F', 'fecha_nacimiento' => '2012-07-30'],
        ];

        foreach ($estudiantes as $estudianteData) {
            $codigo = 'EST' . str_pad($contador, 4, '0', STR_PAD_LEFT);
            $email = strtolower($estudianteData['nombres']) . '.' . strtolower($estudianteData['apellido_paterno']) . '@estudiante.com';
            $email = str_replace(' ', '', $email);

            $user = User::create([
                'email' => $email,
                'password' => Hash::make('estudiante123'),
                'role_id' => $estudianteRole->id,
                'email_verified_at' => now(),
            ]);

            $estudiante = Estudiante::create([
                'ci' => $estudianteData['ci'],
                'rude' => 'RUDE' . str_pad($contador, 6, '0', STR_PAD_LEFT),
                'codigo_estudiante' => $codigo,
                'nombres' => $estudianteData['nombres'],
                'apellido_paterno' => $estudianteData['apellido_paterno'],
                'apellido_materno' => $estudianteData['apellido_materno'],
                'fecha_nacimiento' => $estudianteData['fecha_nacimiento'],
                'genero' => $estudianteData['genero'],
                'email' => $email,
                'user_id' => $user->id,
            ]);



            $contador++;
        }
    }
}
