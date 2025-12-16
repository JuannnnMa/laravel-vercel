<?php

namespace Database\Seeders;

use App\Models\Tutor;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestTutorSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar datos existentes de prueba
        DB::table('users')->where('email', 'test@test.com')->delete();
        DB::table('tutores')->where('email', 'test@test.com')->delete();
        
        $tutorRole = Role::where('nombre', 'tutor')->first();
        
        if (!$tutorRole) {
            echo "ERROR: No se encontró el rol 'tutor'\n";
            return;
        }

        echo "Creando usuario de prueba...\n";
        
        $user = User::create([
            'email' => 'test@test.com',
            'password' => Hash::make('123456'),
            'role_id' => $tutorRole->id,
            'email_verified_at' => now(),
        ]);

        echo "Usuario creado con ID: {$user->id}\n";
        echo "Email: {$user->email}\n";
        echo "Role ID: {$user->role_id}\n";

        $tutor = Tutor::create([
            'ci' => '99999999',
            'nombres' => 'Usuario',
            'apellido_paterno' => 'De',
            'apellido_materno' => 'Prueba',
            'email' => 'test@test.com',
            'celular' => '999999999',
            'ocupacion' => 'Tester',
            'user_id' => $user->id,
        ]);

        echo "Tutor creado con ID: {$tutor->id}\n";

        $user->update([
            'userable_type' => Tutor::class,
            'userable_id' => $tutor->id,
        ]);

        echo "Usuario actualizado con userable_type y userable_id\n";
        
        // Verificar que la contraseña funciona
        $testPassword = '123456';
        $passwordWorks = Hash::check($testPassword, $user->password);
        echo "Verificación de contraseña '123456': " . ($passwordWorks ? 'OK' : 'FALLO') . "\n";
        
        echo "\n=== CREDENCIALES DE PRUEBA ===\n";
        echo "Email: test@test.com\n";
        echo "Password: 123456\n";
        echo "==============================\n";
    }
}
