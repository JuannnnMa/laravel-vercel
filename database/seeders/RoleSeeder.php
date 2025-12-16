<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'administrador', 'descripcion' => 'Administrador del sistema'],
            ['nombre' => 'profesor', 'descripcion' => 'Profesor/Docente'],
            ['nombre' => 'estudiante', 'descripcion' => 'Estudiante'],
            ['nombre' => 'tutor', 'descripcion' => 'Tutor/Padre de familia'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
