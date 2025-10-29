<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@example.com',
            'password' => Hash::make('clave123'),
            'role' => 'admin',
            'code' => 'ADM-001',
            'type' => 'titular',
            'status' => 'active',
        ]);

        // Docente de prueba
        User::create([
            'name' => 'Docente de Prueba',
            'email' => 'prueba@correo.com',
            'password' => Hash::make('clave123'),
            'role' => 'teacher',
            'code' => 'DOC-001',
            'type' => 'titular',
            'phone' => '+591 12345678',
            'status' => 'active',
        ]);

        // Otro docente
        User::create([
            'name' => 'María González',
            'email' => 'maria@correo.com',
            'password' => Hash::make('clave123'),
            'role' => 'teacher',
            'code' => 'DOC-002',
            'type' => 'invitado',
            'phone' => '+591 87654321',
            'status' => 'active',
        ]);
    }
}
