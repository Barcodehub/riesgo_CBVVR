<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $role1 = Role::create([ 'nombre' => 'ADMINISTRADOR' ]);
        $role2 = Role::create([ 'nombre' => 'INSPECTOR' ]);
        $role3 = Role::create([ 'nombre' => 'CLIENTE' ]);


        User::create([
            'nombre' => 'Admin',
            'apellido' => 'Uno',
            'documento' => '12345678',
            'telefono' => '12345678',
            'disponibilidad' => true,
            'email' => 'anderson07rolon@gmail.com',
            'password' => bcrypt('1234'),
            'rol_id' => 1
        ]);

        User::create([
            'nombre' => 'Inspector',
            'apellido' => 'Uno',
            'documento' => '12345679',
            'telefono' => '12345679',
            'disponibilidad' => true,
            'email' => 'inspector@gmail.com',
            'password' => bcrypt('1234'),
            'rol_id' => 2
        ]);

        User::create([
            'nombre' => 'Cliente',
            'apellido' => 'Uno',
            'documento' => '12345673',
            'telefono' => '12345673',
            'disponibilidad' => true,
            'email' => 'cliente@gmail.com',
            'password' => bcrypt('1234'),
            'rol_id' => 3
        ]);
        
    }
}
