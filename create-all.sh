#!/bin/bash

# Migraciones
php artisan make:migration create_anio_academico_table
php artisan make:migration create_periodo_academico_table
php artisan make:migration create_grado_table
php artisan make:migration create_seccion_table
php artisan make:migration create_materia_table
php artisan make:migration create_materia_grado_table
php artisan make:migration create_estudiante_table
php artisan make:migration create_tutor_table
php artisan make:migration create_estudiante_tutor_table
php artisan make:migration create_docente_table
php artisan make:migration create_matricula_table
php artisan make:migration create_asignacion_docente_table
php artisan make:migration create_horario_table
php artisan make:migration create_calificacion_table
php artisan make:migration create_asistencia_table
php artisan make:migration create_roles_table
php artisan make:migration create_users_table

# Modelos
php artisan make:model AnioAcademico
php artisan make:model PeriodoAcademico
php artisan make:model Grado
php artisan make:model Seccion
php artisan make:model Materia
php artisan make:model MateriaGrado
php artisan make:model Estudiante
php artisan make:model Tutor
php artisan make:model Docente
php artisan make:model Matricula
php artisan make:model AsignacionDocente
php artisan make:model Horario
php artisan make:model Calificacion
php artisan make:model Asistencia
php artisan make:model Role
php artisan make:model User

# Controladores
php artisan make:controller Auth/LoginController
php artisan make:controller Admin/AdminDashboardController
php artisan make:controller Admin/ProfesorController
php artisan make:controller Admin/EstudianteController
php artisan make:controller Profesor/ProfesorDashboardController
php artisan make:controller Profesor/NotasController
php artisan make:controller Tutor/TutorDashboardController
php artisan make:controller Tutor/InscripcionController

# Seeders
php artisan make:seeder RoleSeeder
php artisan make:seeder AnioAcademicoSeeder
php artisan make:seeder GradoSeeder
php artisan make:seeder MateriaSeeder

# Middleware
php artisan make:middleware CheckRole

echo "✅ Todos los archivos han sido creados exitosamente"