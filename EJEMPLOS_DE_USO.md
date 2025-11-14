# Ejemplos de Uso - Nuevas Funcionalidades

## Guía Práctica para Desarrolladores

---

## 📝 Tabla de Contenidos

1. [Registrar Incidentes](#1-registrar-incidentes)
2. [Solicitudes de Cambio de Horario](#2-solicitudes-de-cambio-de-horario)
3. [Bitácora de Auditoría](#3-bitácora-de-auditoría)
4. [Generación de Reportes](#4-generación-de-reportes)
5. [Consultar Historial](#5-consultar-historial)

---

## 1. Registrar Incidentes

### Crear un Incidente (Admin)

```php
use App\Models\Incident;
use App\Models\AuditLog;

// En un controlador o tinker
$incident = Incident::create([
    'aula' => 'A-101',
    'incident_date' => now(),
    'type' => 'daño', // daño, mantenimiento, limpieza, otro
    'description' => 'El proyector no enciende. Posible problema eléctrico.',
    'status' => 'reportado', // reportado, en_proceso, resuelto
    'reported_by' => auth()->id(),
    'assigned_to' => 5, // ID del usuario responsable
]);

// Registrar en bitácora
AuditLog::log('created', $incident, null, $incident->toArray());
```

### Actualizar Estado de Incidente

```php
$incident = Incident::find(1);

$oldValues = $incident->toArray();

$incident->update([
    'status' => 'en_proceso',
    'resolution_notes' => 'Técnico revisando el equipo',
]);

AuditLog::log('updated', $incident, $oldValues, $incident->toArray());
```

### Resolver Incidente

```php
$incident = Incident::find(1);

$incident->update([
    'status' => 'resuelto',
    'resolution_notes' => 'Proyector reparado. Se reemplazó el cable de alimentación.',
    'resolved_at' => now(),
]);
```

### Consultar Incidentes

```php
// Todos los incidentes pendientes
$pendingIncidents = Incident::pending()->get();

// Incidentes de un aula específica
$aulaIncidents = Incident::where('aula', 'A-101')
    ->orderBy('incident_date', 'desc')
    ->get();

// Incidentes resueltos en un rango de fechas
$resolvedIncidents = Incident::resolved()
    ->whereBetween('incident_date', ['2025-01-01', '2025-01-31'])
    ->with(['reporter', 'assignee'])
    ->get();
```

---

## 2. Solicitudes de Cambio de Horario

### Docente Crea Solicitud

```php
use App\Models\ScheduleChangeRequest;
use App\Models\Schedule;

// El docente solicita cambiar su horario
$schedule = Schedule::find(10); // Horario actual

$request = ScheduleChangeRequest::create([
    'schedule_id' => $schedule->id,
    'teacher_id' => auth()->id(),
    'new_day_of_week' => 'Miércoles',
    'new_start_time' => '14:00',
    'new_end_time' => '16:00',
    'new_aula' => 'B-205',
    'reason' => 'Tengo un compromiso académico los martes a esa hora.',
    'status' => 'pendiente',
]);

AuditLog::log('created', $request);
```

### Admin Aprueba Solicitud

```php
use App\Models\ScheduleHistory;
use Illuminate\Support\Facades\DB;

$changeRequest = ScheduleChangeRequest::find(1);

DB::beginTransaction();
try {
    $schedule = $changeRequest->schedule;
    $oldValues = $schedule->toArray();

    // Actualizar horario
    $schedule->update([
        'day_of_week' => $changeRequest->new_day_of_week,
        'start_time' => $changeRequest->new_start_time,
        'end_time' => $changeRequest->new_end_time,
        'aula' => $changeRequest->new_aula,
    ]);

    // Actualizar solicitud
    $changeRequest->update([
        'status' => 'aprobado',
        'reviewed_by' => auth()->id(),
        'reviewed_at' => now(),
        'admin_comments' => 'Aprobado. No hay conflictos.',
    ]);

    // Crear registro en historial
    ScheduleHistory::create([
        'schedule_id' => $schedule->id,
        'changed_by' => auth()->id(),
        'change_type' => 'updated',
        'old_values' => $oldValues,
        'new_values' => $schedule->toArray(),
        'reason' => $changeRequest->reason,
        'change_request_id' => $changeRequest->id,
    ]);

    AuditLog::log('approved_schedule_change', $changeRequest);

    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    throw $e;
}
```

### Admin Rechaza Solicitud

```php
$changeRequest = ScheduleChangeRequest::find(1);

$changeRequest->update([
    'status' => 'rechazado',
    'reviewed_by' => auth()->id(),
    'reviewed_at' => now(),
    'admin_comments' => 'No se puede aprobar debido a conflicto con otro grupo en el aula solicitada.',
]);

AuditLog::log('rejected_schedule_change', $changeRequest);
```

### Consultar Solicitudes

```php
// Solicitudes pendientes
$pendingRequests = ScheduleChangeRequest::pending()
    ->with(['teacher', 'schedule.group'])
    ->get();

// Solicitudes de un docente
$teacherRequests = ScheduleChangeRequest::where('teacher_id', 5)
    ->orderBy('created_at', 'desc')
    ->get();

// Solicitudes aprobadas en el último mes
$approvedRequests = ScheduleChangeRequest::approved()
    ->where('reviewed_at', '>=', now()->subMonth())
    ->with(['teacher', 'reviewer'])
    ->get();
```

---

## 3. Bitácora de Auditoría

### Registrar Acciones Manualmente

```php
use App\Models\AuditLog;

// Login
AuditLog::log('login');

// Logout
AuditLog::log('logout');

// Crear registro
AuditLog::log('created', $model, null, $model->toArray());

// Actualizar registro
AuditLog::log('updated', $model, $oldValues, $newValues);

// Eliminar registro
AuditLog::log('deleted', $model, $model->toArray(), null);

// Acción personalizada
AuditLog::create([
    'user_id' => auth()->id(),
    'action' => 'export_report',
    'model_type' => 'Report',
    'model_id' => null,
    'old_values' => null,
    'new_values' => ['type' => 'attendance', 'format' => 'pdf'],
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);
```

### Consultar Bitácora

```php
// Todas las acciones de un usuario
$userLogs = AuditLog::where('user_id', 5)
    ->orderBy('created_at', 'desc')
    ->get();

// Acciones de un tipo específico
$createdLogs = AuditLog::where('action', 'created')
    ->with('user')
    ->get();

// Cambios en un modelo específico
$modelLogs = AuditLog::where('model_type', 'App\\Models\\Schedule')
    ->where('model_id', 10)
    ->get();

// Actividad en un rango de fechas
$recentLogs = AuditLog::whereBetween('created_at', [
    now()->subDays(7),
    now()
])->with('user')->get();

// Acciones desde una IP específica
$ipLogs = AuditLog::where('ip_address', '192.168.1.100')->get();
```

### Ejemplo de Middleware para Auditoría Automática

```php
// app/Http/Middleware/AuditMiddleware.php
namespace App\Http\Middleware;

use Closure;
use App\Models\AuditLog;

class AuditMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Registrar acciones importantes
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => $request->method() . ' ' . $request->path(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
```

---

## 4. Generación de Reportes

### Reporte de Asistencias en PDF

```php
use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf;

// Obtener datos
$attendances = Attendance::with(['teacher', 'schedule.group.subjectModel'])
    ->whereBetween('registered_at', ['2025-01-01', '2025-01-31'])
    ->where('teacher_id', 5)
    ->orderBy('registered_at', 'desc')
    ->get();

// Preparar datos
$data = [
    'attendances' => $attendances,
    'date_from' => '2025-01-01',
    'date_to' => '2025-01-31',
    'teacher' => User::find(5),
    'generated_at' => now(),
];

// Generar PDF
$pdf = Pdf::loadView('admin.reports.attendance-pdf', $data);

// Descargar
return $pdf->download('reporte-asistencia-enero-2025.pdf');

// O mostrar en navegador
return $pdf->stream('reporte-asistencia-enero-2025.pdf');
```

### Reporte de Horarios

```php
use App\Models\Schedule;

$schedules = Schedule::with(['group.teacher', 'group.subjectModel'])
    ->whereHas('group', function ($q) {
        $q->where('teacher_id', 5);
    })
    ->orderBy('day_of_week')
    ->orderBy('start_time')
    ->get();

$data = [
    'schedules' => $schedules,
    'teacher' => User::find(5),
    'semester' => '2025-1',
];

$pdf = Pdf::loadView('admin.reports.schedule-pdf', $data);
return $pdf->download('horarios-docente-2025-1.pdf');
```

### Reporte de Docentes

```php
$teachers = User::whereIn('role', ['teacher', 'docente'])
    ->where('status', 'active')
    ->with('subjects')
    ->orderBy('name')
    ->get();

$data = [
    'teachers' => $teachers,
    'total' => $teachers->count(),
    'by_type' => [
        'titular' => $teachers->where('type', 'titular')->count(),
        'invitado' => $teachers->where('type', 'invitado')->count(),
        'auxiliar' => $teachers->where('type', 'auxiliar')->count(),
    ],
];

$pdf = Pdf::loadView('admin.reports.teacher-pdf', $data);
return $pdf->download('lista-docentes-' . date('Y-m-d') . '.pdf');
```

### Personalizar PDF

```php
$pdf = Pdf::loadView('admin.reports.custom', $data);

// Configurar orientación
$pdf->setPaper('a4', 'landscape'); // horizontal
$pdf->setPaper('a4', 'portrait');  // vertical

// Configurar opciones
$pdf->setOptions([
    'defaultFont' => 'Arial',
    'isHtml5ParserEnabled' => true,
    'isRemoteEnabled' => true,
]);

return $pdf->download('reporte.pdf');
```

---

## 5. Consultar Historial

### Historial de Cambios de un Horario

```php
use App\Models\ScheduleHistory;

$schedule = Schedule::find(10);

$history = ScheduleHistory::where('schedule_id', $schedule->id)
    ->with(['user', 'changeRequest'])
    ->orderBy('created_at', 'desc')
    ->get();

foreach ($history as $change) {
    echo "Cambio realizado por: " . $change->user->name . "\n";
    echo "Fecha: " . $change->created_at . "\n";
    echo "Tipo: " . $change->change_type . "\n";
    echo "Valores anteriores: " . json_encode($change->old_values) . "\n";
    echo "Valores nuevos: " . json_encode($change->new_values) . "\n";
    echo "Razón: " . $change->reason . "\n\n";
}
```

### Historial de un Docente

```php
$teacherId = 5;

$history = ScheduleHistory::whereHas('schedule.group', function ($q) use ($teacherId) {
    $q->where('teacher_id', $teacherId);
})
->with(['schedule.group', 'user'])
->orderBy('created_at', 'desc')
->get();
```

### Cambios en un Rango de Fechas

```php
$history = ScheduleHistory::whereBetween('created_at', [
    '2025-01-01',
    '2025-01-31'
])
->with(['schedule.group.teacher', 'user'])
->get();
```

### Cambios por Tipo

```php
// Solo creaciones
$created = ScheduleHistory::where('change_type', 'created')->get();

// Solo actualizaciones
$updated = ScheduleHistory::where('change_type', 'updated')->get();

// Solo eliminaciones
$deleted = ScheduleHistory::where('change_type', 'deleted')->get();
```

---

## 🧪 Ejemplos de Testing

### Test de Incidentes

```php
// tests/Feature/IncidentTest.php
public function test_admin_can_create_incident()
{
    $admin = User::factory()->create(['role' => 'admin']);
    
    $response = $this->actingAs($admin)->post('/admin/incidents', [
        'aula' => 'A-101',
        'incident_date' => now(),
        'type' => 'daño',
        'description' => 'Test incident',
        'status' => 'reportado',
    ]);
    
    $response->assertRedirect('/admin/incidents');
    $this->assertDatabaseHas('incidents', [
        'aula' => 'A-101',
        'type' => 'daño',
    ]);
}
```

### Test de Solicitudes de Cambio

```php
public function test_teacher_can_request_schedule_change()
{
    $teacher = User::factory()->create(['role' => 'teacher']);
    $schedule = Schedule::factory()->create();
    
    $response = $this->actingAs($teacher)->post('/teacher/schedule-change-requests', [
        'schedule_id' => $schedule->id,
        'new_day_of_week' => 'Miércoles',
        'new_start_time' => '14:00',
        'new_end_time' => '16:00',
        'reason' => 'Test reason',
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('schedule_change_requests', [
        'schedule_id' => $schedule->id,
        'status' => 'pendiente',
    ]);
}
```

---

## 📊 Consultas Útiles

### Estadísticas de Incidentes

```php
// Incidentes por estado
$stats = Incident::selectRaw('status, COUNT(*) as count')
    ->groupBy('status')
    ->get();

// Incidentes por tipo
$byType = Incident::selectRaw('type, COUNT(*) as count')
    ->groupBy('type')
    ->get();

// Aulas con más incidentes
$topAulas = Incident::selectRaw('aula, COUNT(*) as count')
    ->groupBy('aula')
    ->orderBy('count', 'desc')
    ->limit(10)
    ->get();
```

### Estadísticas de Solicitudes

```php
// Solicitudes por estado
$requestStats = ScheduleChangeRequest::selectRaw('status, COUNT(*) as count')
    ->groupBy('status')
    ->get();

// Docentes con más solicitudes
$topTeachers = ScheduleChangeRequest::selectRaw('teacher_id, COUNT(*) as count')
    ->groupBy('teacher_id')
    ->orderBy('count', 'desc')
    ->with('teacher')
    ->get();

// Tasa de aprobación
$total = ScheduleChangeRequest::count();
$approved = ScheduleChangeRequest::where('status', 'aprobado')->count();
$approvalRate = ($approved / $total) * 100;
```

---

## 🔔 Notificaciones (Ejemplo para implementar)

```php
// app/Notifications/ScheduleChangeRequestCreated.php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ScheduleChangeRequestCreated extends Notification
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva Solicitud de Cambio de Horario')
            ->line('Se ha recibido una nueva solicitud de cambio de horario.')
            ->line('Docente: ' . $this->request->teacher->name)
            ->action('Ver Solicitud', url('/admin/schedule-change-requests/' . $this->request->id))
            ->line('Por favor revisa y procesa la solicitud.');
    }
}

// Uso
$admins = User::where('role', 'admin')->get();
Notification::send($admins, new ScheduleChangeRequestCreated($request));
```

---

## 💡 Tips y Mejores Prácticas

### 1. Usar Transacciones para Operaciones Complejas

```php
DB::beginTransaction();
try {
    // Múltiples operaciones
    $schedule->update(...);
    $request->update(...);
    ScheduleHistory::create(...);
    
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    throw $e;
}
```

### 2. Eager Loading para Optimizar Consultas

```php
// ❌ Malo (N+1 queries)
$incidents = Incident::all();
foreach ($incidents as $incident) {
    echo $incident->reporter->name;
}

// ✅ Bueno
$incidents = Incident::with(['reporter', 'assignee'])->get();
```

### 3. Usar Scopes para Consultas Comunes

```php
// En el modelo
public function scopeActive($query)
{
    return $query->where('status', 'active');
}

// Uso
$activeIncidents = Incident::active()->get();
```

### 4. Validar Datos Siempre

```php
$validated = $request->validate([
    'aula' => 'required|string|max:50',
    'incident_date' => 'required|date',
    'type' => 'required|in:daño,mantenimiento,limpieza,otro',
]);
```

---

**Fecha:** 13 de noviembre de 2025  
**Versión:** 1.0
