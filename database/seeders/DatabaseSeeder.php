<?php

namespace Database\Seeders;

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

        // Default test users
        User::factory()->create([
            'name' => 'Super Usuario',
            'email' => 'admin@example.com',
            'role' => 'superusuario',
        ]);

        User::factory()->create([
            'name' => 'Docente Ejemplo',
            'email' => 'docente@example.com',
            'role' => 'docente',
        ]);
    }
}
