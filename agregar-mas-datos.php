<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🌱 Agregando más datos a la base de datos...\n\n";

// 1. Agregar más materias
echo "📚 Agregando más materias...\n";
$nuevasMaterias = [
    ['name' => 'Matemáticas II', 'code' => 'MAT2', 'credits' => 4],
    ['name' => 'Física II', 'code' => 'FIS2', 'credits' => 4],
    ['name' => 'Química General', 'code' => 'QUIM', 'credits' => 4],
    ['name' => 'Programación II', 'code' => 'PROG2', 'credits' => 5],
    ['name' => 'Estructuras de Datos', 'code' => 'ESTDAT', 'credits' => 5],
    ['name' => 'Algoritmos', 'code' => 'ALG', 'credits' => 4],
    ['name' => 'Sistemas Operativos', 'code' => 'SO', 'credits' => 4],
    ['name' => 'Ingeniería de Software', 'code' => 'INGSOFT', 'credits' => 5],
    ['name' => 'Arquitectura de Computadoras', 'code' => 'ARQCOM', 'credits' => 4],
    ['name' => 'Cálculo I', 'code' => 'CALC1', 'credits' => 5],
    ['name' => 'Cálculo II', 'code' => 'CALC2', 'credits' => 5],
    ['name' => 'Álgebra Lineal', 'code' => 'ALGLIN', 'credits' => 4],
    ['name' => 'Estadística', 'code' => 'ESTAD', 'credits' => 4],
    ['name' => 'Probabilidad', 'code' => 'PROB', 'credits' => 3],
    ['name' => 'Inglés I', 'code' => 'ING1', 'credits' => 3],
];

foreach ($nuevasMaterias as $materia) {
    $exists = DB::table('subjects')->where('name', $materia['name'])->exists();
    if (!$exists) {
        DB::statement("INSERT INTO subjects (name, code, credits, active, created_at, updated_at) 
                       VALUES (?, ?, ?, true, NOW(), NOW())", 
                       [$materia['name'], $materia['code'], $materia['credits']]);
        echo "  ✓ {$materia['name']}\n";
    }
}
echo "\n";

// 2. Crear grupos para todas las materias
echo "📋 Creando grupos...\n";
$subjects = DB::table('subjects')->get();
$teachers = DB::table('users')->whereIn('role', ['teacher', 'docente'])->get();
$aulas = [];

// Generar aulas
for ($i = 1; $i <= 25; $i++) {
    $aulas[] = 'A-' . str_pad($i, 3, '0', STR_PAD_LEFT);
}
for ($i = 1; $i <= 5; $i++) {
    $aulas[] = 'LAB-' . $i;
}
$aulas[] = 'AUDITORIO';

$days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
$timeSlots = [
    ['07:00:00', '09:00:00'],
    ['09:00:00', '11:00:00'],
    ['11:00:00', '13:00:00'],
    ['14:00:00', '16:00:00'],
    ['16:00:00', '18:00:00'],
    ['18:00:00', '20:00:00'],
];

$gruposCreados = 0;
$horariosCreados = 0;

foreach ($subjects as $subject) {
    // Crear 2-3 grupos por materia
    $numGrupos = rand(2, 3);
    
    for ($g = 1; $g <= $numGrupos; $g++) {
        $groupName = 'Grupo ' . chr(64 + $g); // A, B, C
        
        // Verificar si ya existe
        $exists = DB::table('groups')
            ->where('subject_id', $subject->id)
            ->where('name', $groupName)
            ->exists();
        
        if (!$exists) {
            $teacher = $teachers->random();
            
            $groupId = DB::table('groups')->insertGetId([
                'name' => $groupName,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'subject' => $subject->name,
                'capacity' => rand(25, 35),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $gruposCreados++;
            
            // Crear 2-3 horarios por grupo
            $numHorarios = rand(2, 3);
            for ($h = 0; $h < $numHorarios; $h++) {
                $day = $days[array_rand($days)];
                $timeSlot = $timeSlots[array_rand($timeSlots)];
                $aula = $aulas[array_rand($aulas)];
                
                DB::table('schedules')->insert([
                    'group_id' => $groupId,
                    'day_of_week' => $day,
                    'start_time' => $timeSlot[0],
                    'end_time' => $timeSlot[1],
                    'aula' => $aula,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $horariosCreados++;
            }
        }
    }
}

echo "✓ {$gruposCreados} grupos nuevos creados\n";
echo "✓ {$horariosCreados} horarios nuevos creados\n\n";

// 3. Crear registros de asistencia
echo "📝 Creando registros de asistencia...\n";
$schedules = DB::table('schedules')->get();
$asistenciasCreadas = 0;

foreach ($schedules->random(min(50, $schedules->count())) as $schedule) {
    $group = DB::table('groups')->where('id', $schedule->group_id)->first();
    
    // Crear 5-10 registros de asistencia por horario
    for ($i = 0; $i < rand(5, 10); $i++) {
        DB::table('attendances')->insert([
            'teacher_id' => $group->teacher_id,
            'group_id' => $group->id,
            'schedule_id' => $schedule->id,
            'status' => ['present', 'absent', 'late'][rand(0, 2)],
            'aula' => $schedule->aula,
            'registered_at' => now()->subDays(rand(1, 60)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $asistenciasCreadas++;
    }
}

echo "✓ {$asistenciasCreadas} registros de asistencia creados\n\n";

// 4. Crear incidentes
echo "⚠️ Creando incidentes...\n";
$admin = DB::table('users')->where('role', 'admin')->first();
$incidentTypes = ['daño', 'mantenimiento', 'limpieza', 'otro'];
$descriptions = [
    'Proyector no funciona correctamente',
    'Aire acondicionado averiado',
    'Sillas rotas necesitan reparación',
    'Pizarra necesita limpieza profunda',
    'Computadoras con problemas de red',
    'Luces fundidas en el aula',
    'Puerta con cerradura dañada',
    'Ventanas rotas',
    'Escritorio del profesor inestable',
    'Falta de marcadores para pizarra',
    'Sistema de audio no funciona',
    'Cortinas rotas',
];

$incidentesCreados = 0;

foreach ($aulas as $aula) {
    // 40% de probabilidad de incidente por aula
    if (rand(0, 100) < 40) {
        DB::table('incidents')->insert([
            'aula' => $aula,
            'incident_date' => now()->subDays(rand(1, 30)),
            'type' => $incidentTypes[array_rand($incidentTypes)],
            'description' => $descriptions[array_rand($descriptions)],
            'status' => ['reportado', 'en_proceso', 'resuelto'][rand(0, 2)],
            'reported_by' => $admin->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $incidentesCreados++;
    }
}

echo "✓ {$incidentesCreados} incidentes creados\n\n";

// 5. Crear solicitudes de cambio de horario
echo "🔄 Creando solicitudes de cambio de horario...\n";
$solicitudesCreadas = 0;

foreach ($schedules->random(min(10, $schedules->count())) as $schedule) {
    $group = DB::table('groups')->where('id', $schedule->group_id)->first();
    
    $newDay = $days[array_rand($days)];
    $newTimeSlot = $timeSlots[array_rand($timeSlots)];
    $newAula = $aulas[array_rand($aulas)];
    
    DB::table('schedule_change_requests')->insert([
        'schedule_id' => $schedule->id,
        'teacher_id' => $group->teacher_id,
        'new_day_of_week' => $newDay,
        'new_start_time' => $newTimeSlot[0],
        'new_end_time' => $newTimeSlot[1],
        'new_aula' => $newAula,
        'reason' => 'Solicito cambio de horario por conflicto con otra materia',
        'status' => ['pendiente', 'aprobado', 'rechazado'][rand(0, 2)],
        'created_at' => now()->subDays(rand(1, 15)),
        'updated_at' => now(),
    ]);
    $solicitudesCreadas++;
}

echo "✓ {$solicitudesCreadas} solicitudes de cambio creadas\n\n";

// 6. Crear historial de cambios
echo "📜 Creando historial de cambios...\n";
$historialesCreados = 0;

foreach ($schedules->random(min(15, $schedules->count())) as $schedule) {
    DB::table('schedule_histories')->insert([
        'schedule_id' => $schedule->id,
        'changed_by' => $admin->id,
        'change_type' => ['created', 'updated'][rand(0, 1)],
        'old_values' => json_encode(['aula' => $schedule->aula]),
        'new_values' => json_encode(['aula' => $aulas[array_rand($aulas)]]),
        'reason' => 'Cambio administrativo',
        'created_at' => now()->subDays(rand(1, 20)),
        'updated_at' => now(),
    ]);
    $historialesCreados++;
}

echo "✓ {$historialesCreados} registros de historial creados\n\n";

// Resumen final
echo "✅ ¡Datos agregados exitosamente!\n\n";
echo "📊 TOTALES FINALES:\n";
echo "   - Usuarios: " . DB::table('users')->count() . "\n";
echo "   - Materias: " . DB::table('subjects')->count() . "\n";
echo "   - Grupos: " . DB::table('groups')->count() . "\n";
echo "   - Horarios: " . DB::table('schedules')->count() . "\n";
echo "   - Asistencias: " . DB::table('attendances')->count() . "\n";
echo "   - Incidentes: " . DB::table('incidents')->count() . "\n";
echo "   - Solicitudes de cambio: " . DB::table('schedule_change_requests')->count() . "\n";
echo "   - Historial de cambios: " . DB::table('schedule_histories')->count() . "\n\n";
echo "🔑 CREDENCIALES:\n";
echo "   Admin: admin@example.com / password\n";
echo "   Docentes: docente1@example.com hasta docente20@example.com / password\n";
