<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n=== Usuarios en Supabase ===\n\n";

try {
    $users = \App\Models\User::all(['id', 'name', 'email', 'role']);
    
    if ($users->count() === 0) {
        echo "❌ No hay usuarios en la base de datos.\n";
        echo "Ejecuta: php artisan db:seed --class=UserSeeder\n\n";
    } else {
        echo "Total de usuarios: " . $users->count() . "\n\n";
        
        foreach ($users as $user) {
            echo "ID: {$user->id}\n";
            echo "Nombre: {$user->name}\n";
            echo "Email: {$user->email}\n";
            echo "Rol: {$user->role}\n";
            echo "---\n";
        }
        
        echo "\n💡 Usa estos emails para login (contraseña: password)\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}
