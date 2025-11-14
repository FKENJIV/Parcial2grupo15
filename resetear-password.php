<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n=== Reseteando Contraseñas ===\n\n";

try {
    // Resetear contraseña del admin
    $admin = \App\Models\User::where('email', 'admin@example.com')->first();
    if ($admin) {
        $admin->password = \Illuminate\Support\Facades\Hash::make('password');
        $admin->save();
        echo "✅ Admin actualizado: admin@example.com / password\n";
    }
    
    // Resetear contraseña de los docentes
    $teacher1 = \App\Models\User::where('email', 'prueba@correo.com')->first();
    if ($teacher1) {
        $teacher1->password = \Illuminate\Support\Facades\Hash::make('password');
        $teacher1->save();
        echo "✅ Docente actualizado: prueba@correo.com / password\n";
    }
    
    $teacher2 = \App\Models\User::where('email', 'maria@correo.com')->first();
    if ($teacher2) {
        $teacher2->password = \Illuminate\Support\Facades\Hash::make('password');
        $teacher2->save();
        echo "✅ Docente actualizado: maria@correo.com / password\n";
    }
    
    echo "\n✅ Contraseñas actualizadas exitosamente!\n";
    echo "\nAhora puedes hacer login con:\n";
    echo "- admin@example.com / password\n";
    echo "- prueba@correo.com / password\n";
    echo "- maria@correo.com / password\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
}
