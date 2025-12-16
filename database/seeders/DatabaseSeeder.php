<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AreaSeeder::class,
            MateriaSeeder::class,
            CursoSeeder::class,
            AnioAcademicoSeeder::class,
            ParaleloSeeder::class,
            TutorSeeder::class,
            EstudianteSeeder::class,
            ProfesorSeeder::class,
            ParentescoSeeder::class,
            InscripcionEstSeeder::class,
            AsignacionDocenteSeeder::class,
            CursoMateriaSeeder::class,
        ]);
    }
}
