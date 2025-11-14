<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Incident;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "🌱 Iniciando población de base de datos...\n\n";

        // 1. Crear Admin (si no existe)
        echo "👑 Creando administrador...\n";
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador del Sistema',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        echo "✓ Admin creado: {$admin->email} / password\n\n";

        // 2. Crear 20 Docentes
        echo "👨‍🏫 Creando 20 docentes...\n";
        $teachers = [];
        $teacherNames = [
            'Juan Pérez', 'María García', 'Carlos López', 'Ana Martínez', 'Luis Rodríguez',
            'Carmen Fernández', 'José González', 'Laura Sánchez', 'Miguel Ramírez', 'Isabel Torres',
            'Francisco Flores', 'Patricia Morales', 'Roberto Jiménez', 'Elena Ruiz', 'Diego Herrera',
            'Sofía Castro', 'Andrés Ortiz', 'Valentina Romero', 'Gabriel Silva', 'Camila Vargas'
        ];

        foreach ($teacherNames as $index => $name) {
            $email = 'docente' . ($index + 1) . '@example.com';
            $teacher = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                ]
            );
            $teachers[] = $teacher;
            echo "  ✓ {$name} ({$email})\n";
        }
        echo "\n";

        // 3. Crear Materias
        echo "📚 Creando materias...\n";
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
            echo "  ✓ {$subjectName}\n";
        }
        echo "\n";

        // 4. Crear 31 Aulas
        echo "🏫 Creando 31 aulas...\n";
        $aulas = [];
        
        // Aulas normales (25 aulas - capacidad 90)
        for ($i = 1; $i <= 25; $i++) {
            $aulas[] = [
                'nombre' => 'A-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'capacidad' => 90,
                'tipo' => 'aula'
            ];
        }
        
        // Laboratorios (5 aulas - capacidad 60)
        for ($i = 1; $i <= 5; $i++) {
            $aulas[] = [
                'nombre' => 'LAB-' . $i,
                'capacidad' => 60,
                'tipo' => 'laboratorio'
            ];
        }
        
        // Auditorio (1 - capacidad 120)
        $aulas[] = [
            'nombre' => 'AUDITORIO',
            'capacidad' => 120,
            'tipo' => 'auditorio'
        ];

        foreach ($aulas as $aula) {
            echo "  ✓ {$aula['nombre']} (Capacidad: {$aula['capacidad']})\n";
        }
        echo "\n";

        // 5. Crear Grupos y Horarios
        echo "📋 Creando grupos y horarios...\n";
        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $timeSlots = [
            ['07:00:00', '09:00:00'],
            ['09:00:00', '11:00:00'],
            ['11:00:00', '13:00:00'],
            ['14:00:00', '16:00:00'],
            ['16:00:00', '18:00:00'],
            ['18:00:00', '20:00:00'],
        ];

        $groupCounter = 1;
        foreach ($subjects as $subject) {
            // Crear 2-3 grupos por materia
            $numGroups = rand(2, 3);
            for ($g = 1; $g <= $numGroups; $g++) {
                $teacher = $teachers[array_rand($teachers)];
                $aula = $aulas[array_rand($aulas)];
                
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

                // Crear 2-3 horarios por grupo
                $numSchedules = rand(2, 3);
                for ($s = 0; $s < $numSchedules; $s++) {
                    $day = $days[array_rand($days)];
                    $timeSlot = $timeSlots[array_rand($timeSlots)];
                    
                    Schedule::firstOrCreate(
                        [
                            'group_id' => $group->id,
                            'day_of_week' => $day,
                            'start_time' => $timeSlot[0],
                        ],
                        [
                            'end_time' => $timeSlot[1],
                            'aula' => $aula['nombre'],
                        ]
                    );
                }
                
                echo "  ✓ Grupo {$groupCounter}: {$subject->name} - {$group->group_name} ({$teacher->name})\n";
                $groupCounter++;
            }
        }
        echo "\n";

        // 6. Crear registros de asistencia
        echo "📝 Creando registros de asistencia...\n";
        $schedules = Schedule::with('group')->get();
        foreach ($schedules->random(min(30, $schedules->count())) as $schedule) {
            for ($i = 0; $i < 5; $i++) {
                Attendance::create([
                    'teacher_id' => $schedule->group->teacher_id,
                    'schedule_id' => $schedule->id,
                    'date' => now()->subDays(rand(1, 30)),
                    'status' => ['presente', 'ausente', 'tardanza'][rand(0, 2)],
                    'notes' => rand(0, 1) ? 'Clase normal' : null,
                ]);
            }
        }
        echo "✓ 150 registros de asistencia creados\n\n";

        // 7. Crear incidentes
        echo "⚠️ Creando incidentes...\n";
        $incidentTypes = ['daño', 'mantenimiento', 'limpieza', 'otro'];
        $incidentDescriptions = [
            'Proyector no funciona correctamente',
            'Aire acondicionado averiado',
            'Sillas rotas necesitan reparación',
            'Pizarra necesita limpieza profunda',
            'Computadoras con problemas de red',
            'Luces fundidas',
            'Puerta con cerradura dañada',
            'Ventanas rotas',
        ];

        foreach ($aulas as $aula) {
            if (rand(0, 2) == 0) { // 33% de probabilidad de incidente por aula
                Incident::create([
                    'aula' => $aula['nombre'],
                    'incident_date' => now()->subDays(rand(1, 15)),
                    'type' => $incidentTypes[array_rand($incidentTypes)],
                    'description' => $incidentDescriptions[array_rand($incidentDescriptions)],
                    'status' => ['reportado', 'en_proceso', 'resuelto'][rand(0, 2)],
                    'reported_by' => $admin->id,
                ]);
                echo "  ✓ Incidente en {$aula['nombre']}\n";
            }
        }
        echo "\n";

        echo "✅ ¡Base de datos poblada exitosamente!\n\n";
        echo "📊 RESUMEN:\n";
        echo "   - 1 Administrador\n";
        echo "   - 20 Docentes\n";
        echo "   - " . count($subjects) . " Materias\n";
        echo "   - 31 Aulas (25 normales + 5 labs + 1 auditorio)\n";
        echo "   - " . Group::count() . " Grupos\n";
        echo "   - " . Schedule::count() . " Horarios\n";
        echo "   - " . Attendance::count() . " Registros de asistencia\n";
        echo "   - " . Incident::count() . " Incidentes\n\n";
        echo "🔑 CREDENCIALES:\n";
        echo "   Admin: admin@example.com / password\n";
        echo "   Docentes: docente1@example.com hasta docente20@example.com / password\n";
    }
}
