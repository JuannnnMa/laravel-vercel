<?php

namespace Database\Seeders;

use App\Models\Tutor;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TutorSeeder extends Seeder
{
    public function run(): void
    {
        $tutorRole = Role::where('nombre', 'tutor')->first();

        $tutores = [
            ['ci' => '12345678', 'nombres' => 'Juan Carlos', 'apellido_paterno' => 'García', 'apellido_materno' => 'López', 'email' => 'juan.garcia@gmail.com', 'celular' => '987654321', 'ocupacion' => 'Ingeniero'],
            ['ci' => '23456789', 'nombres' => 'María Elena', 'apellido_paterno' => 'Rodríguez', 'apellido_materno' => 'Pérez', 'email' => 'maria.rodriguez@gmail.com', 'celular' => '987654322', 'ocupacion' => 'Doctora'],
            ['ci' => '34567890', 'nombres' => 'Pedro Luis', 'apellido_paterno' => 'Martínez', 'apellido_materno' => 'Sánchez', 'email' => 'pedro.martinez@gmail.com', 'celular' => '987654323', 'ocupacion' => 'Comerciante'],
        ];

        foreach ($tutores as $tutorData) {
            $user = User::create([
                'email' => $tutorData['email'],
                'password' => Hash::make('tutor123'),
                'role_id' => $tutorRole->id,
                'email_verified_at' => now(),
            ]);

            $tutor = Tutor::create([
                'ci' => $tutorData['ci'],
                'nombres' => $tutorData['nombres'],
                'apellido_paterno' => $tutorData['apellido_paterno'],
                'apellido_materno' => $tutorData['apellido_materno'],
                'email' => $tutorData['email'],
                'celular' => $tutorData['celular'],
                'ocupacion' => $tutorData['ocupacion'],
                'user_id' => $user->id,
            ]);


        }
    }
}
