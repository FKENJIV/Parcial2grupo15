<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "👨‍🏫 Poblando campos completos de docentes...\n\n";

// Obtener todos los docentes
$docentes = DB::table('users')->whereIn('role', ['teacher', 'docente'])->get();

$especialidades = [
    'Matemáticas',
    'Física',
    'Química',
    'Programación',
    'Redes y Telecomunicaciones',
    'Base de Datos',
    'Ingeniería de Software',
    'Sistemas Operativos',
    'Arquitectura de Computadoras',
    'Inteligencia Artificial',
    'Seguridad Informática',
    'Desarrollo Web',
    'Desarrollo Móvil',
    'Ciencia de Datos',
    'Cloud Computing',
];

$tipos = ['Tiempo Completo', 'Medio Tiempo', 'Por Horas'];
$estados = ['activo', 'inactivo', 'licencia'];

$telefonos = [
    '591-2-2234567', '591-2-2345678', '591-2-2456789', '591-2-2567890',
    '591-3-3234567', '591-3-3345678', '591-3-3456789', '591-3-3567890',
    '591-4-4234567', '591-4-4345678', '591-4-4456789', '591-4-4567890',
    '591-7-7234567', '591-7-7345678', '591-7-7456789', '591-7-7567890',
];

$docentesActualizados = 0;

foreach ($docentes as $docente) {
    // Generar código único para el docente
    $codigo = 'DOC-' . str_pad($docente->id, 4, '0', STR_PAD_LEFT);
    
    // Seleccionar 2-3 especialidades aleatorias
    $numEspecialidades = rand(2, 3);
    $especialidadesDocente = [];
    $especialidadesDisponibles = $especialidades;
    shuffle($especialidadesDisponibles);
    
    for ($i = 0; $i < $numEspecialidades; $i++) {
        $especialidadesDocente[] = $especialidadesDisponibles[$i];
    }
    
    // Actualizar el docente
    DB::table('users')
        ->where('id', $docente->id)
        ->update([
            'code' => $codigo,
            'type' => $tipos[array_rand($tipos)],
            'phone' => $telefonos[array_rand($telefonos)],
            'status' => $estados[array_rand($estados)],
            'specialties' => json_encode($especialidadesDocente),
            'email_verified_at' => now(),
            'updated_at' => now(),
        ]);
    
    echo "✓ {$docente->name}\n";
    echo "  - Código: {$codigo}\n";
    echo "  - Teléfono: " . $telefonos[array_rand($telefonos)] . "\n";
    echo "  - Especialidades: " . implode(', ', $especialidadesDocente) . "\n\n";
    
    $docentesActualizados++;
}

echo "✅ {$docentesActualizados} docentes actualizados con información completa\n\n";

// Mostrar resumen de un docente como ejemplo
$docenteEjemplo = DB::table('users')->where('email', 'docente1@example.com')->first();

echo "📋 EJEMPLO DE DOCENTE COMPLETO:\n";
echo "   Nombre: {$docenteEjemplo->name}\n";
echo "   Email: {$docenteEjemplo->email}\n";
echo "   Código: {$docenteEjemplo->code}\n";
echo "   Tipo: {$docenteEjemplo->type}\n";
echo "   Teléfono: {$docenteEjemplo->phone}\n";
echo "   Estado: {$docenteEjemplo->status}\n";
echo "   Especialidades: " . implode(', ', json_decode($docenteEjemplo->specialties)) . "\n";
echo "   Rol: {$docenteEjemplo->role}\n\n";

// Asignar materias a los docentes según sus especialidades
echo "📚 Asignando materias a docentes según especialidades...\n";

$asignacionesCreadas = 0;

foreach ($docentes as $docente) {
    $especialidadesDocente = json_decode(DB::table('users')->where('id', $docente->id)->value('specialties'));
    
    // Buscar materias relacionadas con las especialidades
    foreach ($especialidadesDocente as $especialidad) {
        $materias = DB::table('subjects')
            ->where('name', 'like', '%' . $especialidad . '%')
            ->orWhere('name', 'like', '%' . explode(' ', $especialidad)[0] . '%')
            ->get();
        
        foreach ($materias as $materia) {
            // Verificar si ya existe la asignación
            $existe = DB::table('subject_user')
                ->where('user_id', $docente->id)
                ->where('subject_id', $materia->id)
                ->exists();
            
            if (!$existe) {
                DB::table('subject_user')->insert([
                    'user_id' => $docente->id,
                    'subject_id' => $materia->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $asignacionesCreadas++;
            }
        }
    }
}

echo "✓ {$asignacionesCreadas} asignaciones de materias creadas\n\n";

// Crear grupos adicionales para docentes que no tienen
echo "📋 Asegurando que todos los docentes tengan grupos asignados...\n";

$gruposCreados = 0;

foreach ($docentes as $docente) {
    $gruposExistentes = DB::table('groups')->where('teacher_id', $docente->id)->count();
    
    if ($gruposExistentes < 2) {
        // Asignar 2-3 grupos a este docente
        $gruposNecesarios = rand(2, 3) - $gruposExistentes;
        
        // Obtener materias del docente
        $materiasDocente = DB::table('subject_user')
            ->where('user_id', $docente->id)
            ->pluck('subject_id');
        
        if ($materiasDocente->isEmpty()) {
            // Si no tiene materias asignadas, tomar materias aleatorias
            $materiasDocente = DB::table('subjects')->inRandomOrder()->limit(3)->pluck('id');
        }
        
        foreach ($materiasDocente->take($gruposNecesarios) as $materiaId) {
            $materia = DB::table('subjects')->where('id', $materiaId)->first();
            
            // Crear grupo
            $groupId = DB::table('groups')->insertGetId([
                'name' => 'Grupo ' . chr(65 + rand(0, 2)), // A, B, C
                'subject_id' => $materiaId,
                'teacher_id' => $docente->id,
                'subject' => $materia->name,
                'capacity' => rand(25, 35),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Crear 2 horarios para el grupo
            $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
            $aulas = [];
            for ($i = 1; $i <= 25; $i++) {
                $aulas[] = 'A-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            }
            
            $timeSlots = [
                ['07:00:00', '09:00:00'],
                ['09:00:00', '11:00:00'],
                ['11:00:00', '13:00:00'],
                ['14:00:00', '16:00:00'],
            ];
            
            for ($h = 0; $h < 2; $h++) {
                DB::table('schedules')->insert([
                    'group_id' => $groupId,
                    'day_of_week' => $days[array_rand($days)],
                    'start_time' => $timeSlots[$h][0],
                    'end_time' => $timeSlots[$h][1],
                    'aula' => $aulas[array_rand($aulas)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            $gruposCreados++;
        }
    }
}

echo "✓ {$gruposCreados} grupos adicionales creados\n\n";

echo "✅ ¡Docentes completamente poblados!\n\n";
echo "📊 RESUMEN FINAL:\n";
echo "   - Docentes actualizados: {$docentesActualizados}\n";
echo "   - Asignaciones materia-docente: {$asignacionesCreadas}\n";
echo "   - Grupos adicionales: {$gruposCreados}\n";
echo "   - Total grupos: " . DB::table('groups')->count() . "\n";
echo "   - Total horarios: " . DB::table('schedules')->count() . "\n\n";
echo "🔑 Prueba con: docente1@example.com / password\n";
