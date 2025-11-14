<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Poblando base de datos...\n";

// Crear materias usando DB directo
$materias = [
    ['name' => 'Matemáticas I', 'code' => 'MAT1', 'credits' => 4],
    ['name' => 'Física I', 'code' => 'FIS1', 'credits' => 4],
    ['name' => 'Programación I', 'code' => 'PROG1', 'credits' => 5],
    ['name' => 'Base de Datos', 'code' => 'BD', 'credits' => 4],
    ['name' => 'Redes', 'code' => 'REDES', 'credits' => 4],
];

foreach ($materias as $materia) {
    $exists = DB::table('subjects')->where('name', $materia['name'])->exists();
    if (!$exists) {
        DB::statement("INSERT INTO subjects (name, code, credits, active, created_at, updated_at) 
                       VALUES (?, ?, ?, true, NOW(), NOW())", 
                       [$materia['name'], $materia['code'], $materia['credits']]);
        echo "  ✓ {$materia['name']}\n";
    }
}

echo "✓ Materias creadas\n";
echo "✓ Total usuarios: " . DB::table('users')->count() . "\n";
echo "✓ Total materias: " . DB::table('subjects')->count() . "\n";
echo "✓ Total grupos: " . DB::table('groups')->count() . "\n";
echo "✓ Total horarios: " . DB::table('schedules')->count() . "\n\n";
echo "CREDENCIALES:\n";
echo "Admin: admin@example.com / password\n";
echo "Docentes: docente1@example.com hasta docente20@example.com / password\n";
