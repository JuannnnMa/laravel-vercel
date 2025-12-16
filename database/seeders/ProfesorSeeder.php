<?php

namespace Database\Seeders;

use App\Models\Profesor;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfesorSeeder extends Seeder
{
    public function run(): void
    {
        $profesorRole = Role::where('nombre', 'profesor')->first();
        $contador = 1;

        $profesores = [
            ['ci' => '11111111', 'nombres' => 'Roberto Carlos', 'apellido_paterno' => 'Fernández', 'apellido_materno' => 'Gómez', 'email' => 'roberto.fernandez@colegio.com', 'especialidad' => 'Matemática'],
            ['ci' => '22222222', 'nombres' => 'Carmen Rosa', 'apellido_paterno' => 'Vargas', 'apellido_materno' => 'Silva', 'email' => 'carmen.vargas@colegio.com', 'especialidad' => 'Comunicación'],
            ['ci' => '33333333', 'nombres' => 'Luis Miguel', 'apellido_paterno' => 'Torres', 'apellido_materno' => 'Mendoza', 'email' => 'luis.torres@colegio.com', 'especialidad' => 'Ciencia y Tecnología'],
        ];

        foreach ($profesores as $profesorData) {
            $codigo = 'PROF' . str_pad($contador, 4, '0', STR_PAD_LEFT);

            $user = User::create([
                'email' => $profesorData['email'],
                'password' => Hash::make('profesor123'),
                'role_id' => $profesorRole->id,
                'email_verified_at' => now(),
            ]);

            $profesor = Profesor::create([
                'ci' => $profesorData['ci'],
                'codigo_profesor' => $codigo,
                'item' => 'ITEM-' . $contador,
                'nombres' => $profesorData['nombres'],
                'apellido_paterno' => $profesorData['apellido_paterno'],
                'apellido_materno' => $profesorData['apellido_materno'],
                'email' => $profesorData['email'],
                'celular' => '98765432' . $contador,
                'especialidad' => $profesorData['especialidad'],
                'fecha_ingreso' => '2020-03-01',
                'user_id' => $user->id,
            ]);



            $contador++;
        }
    }
}
