<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        
        // Para PostgreSQL, necesitamos insertar cada registro individualmente
        // para evitar problemas de conversión de tipos boolean
        $subjects = [
            [
                'name' => 'Sistemas de Información 1',
                'code' => 'INF-111',
                'description' => 'Introducción a los sistemas de información empresariales',
                'credits' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Base de Datos',
                'code' => 'INF-112',
                'description' => 'Diseño e implementación de bases de datos relacionales',
                'credits' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Programación',
                'code' => 'INF-113',
                'description' => 'Fundamentos de programación orientada a objetos',
                'credits' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Redes de Computadoras',
                'code' => 'INF-114',
                'description' => 'Conceptos y arquitecturas de redes',
                'credits' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Ingeniería de Software',
                'code' => 'INF-115',
                'description' => 'Metodologías y prácticas de desarrollo de software',
                'credits' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($subjects as $subject) {
            DB::statement(
                "INSERT INTO subjects (name, code, description, credits, active, created_at, updated_at) 
                 VALUES (?, ?, ?, ?, true, ?, ?)",
                [
                    $subject['name'],
                    $subject['code'],
                    $subject['description'],
                    $subject['credits'],
                    $subject['created_at'],
                    $subject['updated_at']
                ]
            );
        }
    }
}
