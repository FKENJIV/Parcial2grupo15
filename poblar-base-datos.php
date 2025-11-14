<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Incident;

echo "🌱 Poblando base de datos...\n\n";

// Verificar datos existentes
echo "📊 Datos actuales:\n";
echo "   Users: " . User::count() . "\n";
echo "   Subjects: " . Subject::count() . "\n";
echo "   Groups: " . Group::count() . "\n";
echo "   Schedules: " . Schedule::count() . "\n\n";

// Crear materias faltantes
echo "📚 Completando materias...\n";
$subjectNames = [
    'Matemáticas I', 'Matemáticas II', 'Física I', 'Física II', 'Química',
    'Programación I', 'Programación II', 'Base de Datos', 'Redes de Computadoras',
    'Sistemas Operativos', 'Ingeniería de Software', 'Arquitectura de Computadoras',
    'Cálculo I', 'Cálculo II', 'Álgebra Lineal', 'Estadística', 'Probabilidad',
    'Inglés I', 'Inglés II', 'Metodología de la Investigación'
];

$subjects = [];
foreach ($subjectNames as $subjectName) {
    $subject = Subject::firstOrCreate(
        ['name' => $subjectName],
        [
            'code' => strtoupper(substr(str_replace(' ', '', $subjectName), 0, 6)),
            'credits' => rand(3, 5),
            'active' => true,
        ]
    );
    $subjects[] = $subject;
}
echo "✓ " . count($subjects) . " materias en total\n\n";

// Obtener docentes
$teachers = User::whereIn('role', ['teacher', 'docente'])->get();
echo "👨‍🏫 Docentes disponibles: " . $teachers->count() . "\n\n";

// Crear grupos y horarios
echo "📋 Creando grupos y horarios...\n";
$days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
$aulas = [];

// Generar lista de aulas
for ($i = 1; $i <= 25; $i++) {
    $aulas[] = 'A-' . str_pad($i, 3, '0', STR_PAD_LEFT);
}
for ($i = 1; $i <= 5; $i++) {
    $aulas[] = 'LAB-' . $i;
}
$aulas[] = 'AUDITORIO';

$timeSlots = [
    ['07:00:00', '09:00:00'],
    ['09:00:00', '11:00:00'],
    ['11:00:00', '13:00:00'],
    ['14:00:00', '16:00:00'],
    ['16:00:00', '18:00:00'],
];

$groupsCreated = 0;
$schedulesCreated = 0;

foreach ($subjects as $subject) {
    // 2 grupos por materia
    for ($g = 1; $g <= 2; $g++) {
        $teacher = $teachers->random();
        
        $group = Group::firstOrCreate(
            [
                'group_name' => 'Grupo ' . chr(64 + $g),
                'subject_id' => $subject->id,
            ],
            [
                'teacher_id' => $teacher->id,
                'subject' => $subject->name,
                'capacity' => rand(25, 35),
            ]
        );

        if ($group->wasRecentlyCreated) {
            $groupsCreated++;
            
            // 2 horarios por grupo
            for ($s = 0; $s < 2; $s++) {
                $schedule = Schedule::create([
                    'group_id' => $group->id,
                    'day_of_week' => $days[array_rand($days)],
                    'start_time' => $timeSlots[$s][0],
                    'end_time' => $timeSlots[$s][1],
                    'aula' => $aulas[array_rand($aulas)],
                ]);
                $schedulesCreated++;
            }
        }
    }
}

echo "✓ {$groupsCreated} grupos nuevos creados\n";
echo "✓ {$schedulesCreated} horarios nuevos creados\n\n";

// Crear incidentes
echo "⚠️ Creando incidentes...\n";
$incidentTypes = ['daño', 'mantenimiento', 'limpieza', 'otro'];
$descriptions = [
    'Proyector no funciona',
    'Aire acondicionado averiado',
    'Sillas rotas',
    'Pizarra sucia',
    'Computadoras lentas',
];

$admin = User::where('role', 'admin')->first();
$incidentsCreated = 0;

foreach (array_slice($aulas, 0, 10) as $aula) {
    Incident::create([
        'aula' => $aula,
        'incident_date' => now()->subDays(rand(1, 15)),
        'type' => $incidentTypes[array_rand($incidentTypes)],
        'description' => $descriptions[array_rand($descriptions)],
        'status' => ['reportado', 'en_proceso', 'resuelto'][rand(0, 2)],
        'reported_by' => $admin->id,
    ]);
    $incidentsCreated++;
}

echo "✓ {$incidentsCreated} incidentes creados\n\n";

echo "✅ ¡Base de datos poblada!\n\n";
echo "📊 TOTALES FINALES:\n";
echo "   - Users: " . User::count() . "\n";
echo "   - Subjects: " . Subject::count() . "\n";
echo "   - Groups: " . Group::count() . "\n";
echo "   - Schedules: " . Schedule::count() . "\n";
echo "   - Incidents: " . Incident::count() . "\n\n";
echo "🔑 CREDENCIALES:\n";
echo "   Admin: admin@example.com / password\n";
echo "   Docentes: docente1@example.com hasta docente20@example.com / password\n";
