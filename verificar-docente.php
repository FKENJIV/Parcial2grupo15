<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$teacher = App\Models\User::where('email', 'docente1@example.com')->first();

echo "👨‍🏫 INFORMACIÓN DEL DOCENTE\n";
echo "==========================\n";
echo "Nombre: {$teacher->name}\n";
echo "Email: {$teacher->email}\n";
echo "Código: {$teacher->code}\n";
echo "Teléfono: {$teacher->phone}\n";
echo "Tipo: {$teacher->type}\n";
echo "Estado: {$teacher->status}\n";
echo "Especialidades: " . ($teacher->specialties ? implode(', ', json_decode($teacher->specialties)) : 'N/A') . "\n\n";

echo "📚 GRUPOS ASIGNADOS\n";
echo "===================\n";
$groups = $teacher->groups;
echo "Total: {$groups->count()}\n\n";

foreach ($groups as $group) {
    echo "Grupo: {$group->name}\n";
    echo "Materia: {$group->subject}\n";
    echo "Capacidad: {$group->capacity}\n";
    
    $schedules = $group->schedules;
    echo "Horarios ({$schedules->count()}):\n";
    
    foreach ($schedules as $schedule) {
        echo "  - {$schedule->day_of_week}: {$schedule->start_time} - {$schedule->end_time} (Aula: {$schedule->aula})\n";
    }
    echo "\n";
}

echo "📝 REGISTROS DE ASISTENCIA\n";
echo "==========================\n";
$attendances = App\Models\Attendance::where('teacher_id', $teacher->id)->count();
echo "Total: {$attendances}\n\n";

echo "🔄 SOLICITUDES DE CAMBIO\n";
echo "========================\n";
$requests = App\Models\ScheduleChangeRequest::where('teacher_id', $teacher->id)->count();
echo "Total: {$requests}\n";
