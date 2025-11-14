<?php

/**
 * Script de Verificación de Instalación
 * Sistema de Gestión Académica
 */

echo "\n";
echo "==========================================\n";
echo "Verificación de Instalación\n";
echo "Sistema de Gestión Académica\n";
echo "==========================================\n\n";

$errors = [];
$warnings = [];
$success = [];

// Verificar que estamos en un proyecto Laravel
if (!file_exists('artisan')) {
    die("❌ Error: No se encontró el archivo artisan. Ejecuta este script desde la raíz del proyecto Laravel.\n");
}

echo "✅ Proyecto Laravel detectado\n\n";

// Verificar modelos
echo "📦 Verificando modelos...\n";
$models = [
    'app/Models/Incident.php',
    'app/Models/ScheduleChangeRequest.php',
    'app/Models/ScheduleHistory.php',
    'app/Models/AuditLog.php',
];

foreach ($models as $model) {
    if (file_exists($model)) {
        $success[] = "✅ Modelo encontrado: " . basename($model);
    } else {
        $errors[] = "❌ Modelo faltante: " . basename($model);
    }
}

// Verificar controladores Admin
echo "\n🎮 Verificando controladores Admin...\n";
$adminControllers = [
    'app/Http/Controllers/Admin/IncidentController.php',
    'app/Http/Controllers/Admin/ScheduleChangeRequestController.php',
    'app/Http/Controllers/Admin/ScheduleHistoryController.php',
    'app/Http/Controllers/Admin/AuditLogController.php',
    'app/Http/Controllers/Admin/ReportController.php',
];

foreach ($adminControllers as $controller) {
    if (file_exists($controller)) {
        $success[] = "✅ Controlador encontrado: " . basename($controller);
    } else {
        $errors[] = "❌ Controlador faltante: " . basename($controller);
    }
}

// Verificar controladores Teacher
echo "\n👨‍🏫 Verificando controladores Teacher...\n";
$teacherControllers = [
    'app/Http/Controllers/Teacher/ScheduleChangeRequestController.php',
];

foreach ($teacherControllers as $controller) {
    if (file_exists($controller)) {
        $success[] = "✅ Controlador encontrado: " . basename($controller);
    } else {
        $errors[] = "❌ Controlador faltante: " . basename($controller);
    }
}

// Verificar migraciones
echo "\n🗄️  Verificando migraciones...\n";
$migrations = [
    'database/migrations/2025_11_13_000001_create_incidents_table.php',
    'database/migrations/2025_11_13_000002_create_schedule_change_requests_table.php',
    'database/migrations/2025_11_13_000003_create_schedule_histories_table.php',
    'database/migrations/2025_11_13_000004_create_audit_logs_table.php',
];

foreach ($migrations as $migration) {
    if (file_exists($migration)) {
        $success[] = "✅ Migración encontrada: " . basename($migration);
    } else {
        $errors[] = "❌ Migración faltante: " . basename($migration);
    }
}

// Verificar rutas
echo "\n🛣️  Verificando archivo de rutas...\n";
if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    
    $routeChecks = [
        'IncidentController' => 'Rutas de incidentes',
        'ScheduleChangeRequestController' => 'Rutas de solicitudes de cambio',
        'ScheduleHistoryController' => 'Rutas de historial',
        'AuditLogController' => 'Rutas de bitácora',
        'ReportController' => 'Rutas de reportes',
    ];
    
    foreach ($routeChecks as $check => $description) {
        if (strpos($routesContent, $check) !== false) {
            $success[] = "✅ $description configuradas";
        } else {
            $warnings[] = "⚠️  $description no encontradas";
        }
    }
}

// Verificar composer.json para DomPDF
echo "\n📄 Verificando dependencias...\n";
if (file_exists('composer.json')) {
    $composerContent = file_get_contents('composer.json');
    if (strpos($composerContent, 'barryvdh/laravel-dompdf') !== false) {
        $success[] = "✅ Laravel DomPDF configurado en composer.json";
    } else {
        $warnings[] = "⚠️  Laravel DomPDF no encontrado en composer.json";
        $warnings[] = "   Ejecuta: composer require barryvdh/laravel-dompdf";
    }
}

// Verificar conexión a base de datos
echo "\n🔌 Verificando conexión a base de datos...\n";
try {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $pdo = DB::connection()->getPdo();
    $success[] = "✅ Conexión a base de datos exitosa";
    
    // Verificar si las tablas existen
    $tables = ['incidents', 'schedule_change_requests', 'schedule_histories', 'audit_logs'];
    foreach ($tables as $table) {
        try {
            DB::table($table)->limit(1)->get();
            $success[] = "✅ Tabla '$table' existe en la base de datos";
        } catch (Exception $e) {
            $warnings[] = "⚠️  Tabla '$table' no existe. Ejecuta: php artisan migrate";
        }
    }
} catch (Exception $e) {
    $warnings[] = "⚠️  No se pudo verificar la base de datos: " . $e->getMessage();
}

// Resumen
echo "\n==========================================\n";
echo "RESUMEN DE VERIFICACIÓN\n";
echo "==========================================\n\n";

if (count($success) > 0) {
    echo "✅ ÉXITOS (" . count($success) . "):\n";
    foreach ($success as $msg) {
        echo "   $msg\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "⚠️  ADVERTENCIAS (" . count($warnings) . "):\n";
    foreach ($warnings as $msg) {
        echo "   $msg\n";
    }
    echo "\n";
}

if (count($errors) > 0) {
    echo "❌ ERRORES (" . count($errors) . "):\n";
    foreach ($errors as $msg) {
        echo "   $msg\n";
    }
    echo "\n";
}

// Conclusión
echo "==========================================\n";
if (count($errors) === 0 && count($warnings) === 0) {
    echo "✅ INSTALACIÓN COMPLETA Y CORRECTA\n";
    echo "==========================================\n\n";
    echo "Próximos pasos:\n";
    echo "1. Crear las vistas necesarias en resources/views/\n";
    echo "2. Probar las rutas: php artisan route:list\n";
    echo "3. Acceder a /admin/incidents para probar\n";
} elseif (count($errors) === 0) {
    echo "⚠️  INSTALACIÓN COMPLETA CON ADVERTENCIAS\n";
    echo "==========================================\n\n";
    echo "Revisa las advertencias arriba y ejecuta los comandos sugeridos.\n";
} else {
    echo "❌ INSTALACIÓN INCOMPLETA\n";
    echo "==========================================\n\n";
    echo "Hay errores que deben ser corregidos antes de continuar.\n";
}

echo "\n";
