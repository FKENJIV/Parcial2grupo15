<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📝 Poblando historial del docente...\n\n";

$teacher = App\Models\User::where('email', 'docente1@example.com')->first();
$groups = $teacher->groups;

// 1. Crear registros de asistencia (últimos 60 días)
echo "📅 Creando registros de asistencia...\n";
$asistenciasCreadas = 0;

foreach ($groups as $group) {
    foreach ($group->schedules as $schedule) {
        // Crear 20 registros de asistencia por horario
        for ($i = 0; $i < 20; $i++) {
            App\Models\Attendance::create([
                'teacher_id' => $teacher->id,
                'group_id' => $group->id,
                'schedule_id' => $schedule->id,
                'status' => ['present', 'present', 'present', 'late'][rand(0, 3)], // 75% presente
                'aula' => $schedule->aula,
                'registered_at' => now()->subDays(rand(1, 60)),
            ]);
            $asistenciasCreadas++;
        }
    }
}

echo "✓ {$asistenciasCreadas} registros de asistencia creados\n\n";

// 2. Crear solicitudes de cambio de horario
echo "🔄 Creando solicitudes de cambio...\n";
$solicitudesCreadas = 0;

$days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
$aulas = [];
for ($i = 1; $i <= 25; $i++) {
    $aulas[] = 'A-' . str_pad($i, 3, '0', STR_PAD_LEFT);
}

foreach ($groups as $group) {
    $schedule = $group->schedules->first();
    
    // Crear 2 solicitudes por grupo
    for ($s = 0; $s < 2; $s++) {
        $status = ['pendiente', 'aprobado', 'rechazado'][rand(0, 2)];
        
        $request = App\Models\ScheduleChangeRequest::create([
            'schedule_id' => $schedule->id,
            'teacher_id' => $teacher->id,
            'new_day_of_week' => $days[array_rand($days)],
            'new_start_time' => ['08:00:00', '10:00:00', '14:00:00'][rand(0, 2)],
            'new_end_time' => ['10:00:00', '12:00:00', '16:00:00'][rand(0, 2)],
            'new_aula' => $aulas[array_rand($aulas)],
            'reason' => [
                'Tengo un conflicto con otra materia en ese horario',
                'Solicito cambio por motivos personales',
                'El aula actual no tiene el equipamiento necesario',
                'Prefiero un horario más temprano para mejor rendimiento de los estudiantes'
            ][rand(0, 3)],
            'status' => $status,
            'created_at' => now()->subDays(rand(5, 30)),
        ]);
        
        // Si está aprobada o rechazada, agregar datos del revisor
        if ($status != 'pendiente') {
            $admin = App\Models\User::where('role', 'admin')->first();
            $request->update([
                'reviewed_by' => $admin->id,
                'reviewed_at' => now()->subDays(rand(1, 5)),
                'admin_comments' => $status == 'aprobado' 
                    ? 'Solicitud aprobada. El cambio es razonable.' 
                    : 'No hay disponibilidad en el horario solicitado.',
            ]);
        }
        
        $solicitudesCreadas++;
    }
}

echo "✓ {$solicitudesCreadas} solicitudes de cambio creadas\n\n";

// 3. Crear más grupos para el docente
echo "📚 Creando grupos adicionales...\n";
$gruposCreados = 0;

$subjects = App\Models\Subject::whereIn('name', ['Desarrollo Web', 'Sistemas Operativos'])->get();

foreach ($subjects->take(2) as $subject) {
    $group = App\Models\Group::create([
        'name' => 'Grupo ' . chr(67 + $gruposCreados), // C, D, E
        'subject_id' => $subject->id,
        'teacher_id' => $teacher->id,
        'subject' => $subject->name,
        'capacity' => rand(25, 35),
    ]);
    
    // Crear 3 horarios por grupo
    $timeSlots = [
        ['07:00:00', '09:00:00'],
        ['11:00:00', '13:00:00'],
        ['16:00:00', '18:00:00'],
    ];
    
    for ($h = 0; $h < 3; $h++) {
        App\Models\Schedule::create([
            'group_id' => $group->id,
            'day_of_week' => $days[($h + 1) % 5],
            'start_time' => $timeSlots[$h][0],
            'end_time' => $timeSlots[$h][1],
            'aula' => $aulas[array_rand($aulas)],
        ]);
    }
    
    $gruposCreados++;
}

echo "✓ {$gruposCreados} grupos adicionales creados\n\n";

// Resumen final
echo "✅ ¡Historial del docente poblado!\n\n";
echo "📊 RESUMEN FINAL:\n";
echo "   - Registros de asistencia: " . App\Models\Attendance::where('teacher_id', $teacher->id)->count() . "\n";
echo "   - Solicitudes de cambio: " . App\Models\ScheduleChangeRequest::where('teacher_id', $teacher->id)->count() . "\n";
echo "   - Grupos asignados: " . $teacher->groups()->count() . "\n";
echo "   - Total horarios: " . App\Models\Schedule::whereIn('group_id', $teacher->groups()->pluck('id'))->count() . "\n\n";
echo "🔑 Inicia sesión con: docente1@example.com / password\n";
