# Instrucciones de Implementación - Nuevas Funcionalidades

## 📋 Resumen

Este documento contiene las instrucciones para implementar las funcionalidades faltantes en el Sistema de Gestión Académica.

---

## 🚀 Instalación Rápida

### Windows
```cmd
install-nuevas-funcionalidades.bat
```

### Linux/Mac
```bash
chmod +x install-nuevas-funcionalidades.sh
./install-nuevas-funcionalidades.sh
```

---

## 📦 Instalación Manual

Si prefieres instalar manualmente, sigue estos pasos:

### 1. Instalar Dependencias

```bash
composer require barryvdh/laravel-dompdf
```

### 2. Ejecutar Migraciones

```bash
php artisan migrate
```

Las siguientes tablas serán creadas:
- `incidents` - Para registrar incidentes de aulas
- `schedule_change_requests` - Para solicitudes de cambio de horario
- `schedule_histories` - Para historial de cambios
- `audit_logs` - Para bitácora de auditoría

### 3. Limpiar y Optimizar

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
```

---

## 📁 Archivos Creados

### Migraciones
- `database/migrations/2025_11_13_000001_create_incidents_table.php`
- `database/migrations/2025_11_13_000002_create_schedule_change_requests_table.php`
- `database/migrations/2025_11_13_000003_create_schedule_histories_table.php`
- `database/migrations/2025_11_13_000004_create_audit_logs_table.php`

### Modelos
- `app/Models/Incident.php`
- `app/Models/ScheduleChangeRequest.php`
- `app/Models/ScheduleHistory.php`
- `app/Models/AuditLog.php`

### Controladores Admin
- `app/Http/Controllers/Admin/IncidentController.php`
- `app/Http/Controllers/Admin/ScheduleChangeRequestController.php`
- `app/Http/Controllers/Admin/ScheduleHistoryController.php`
- `app/Http/Controllers/Admin/AuditLogController.php`
- `app/Http/Controllers/Admin/ReportController.php`

### Controladores Teacher
- `app/Http/Controllers/Teacher/ScheduleChangeRequestController.php`

### Rutas
- Actualizadas en `routes/web.php`

---

## 🎯 Casos de Uso Implementados

### ✅ CU7: EMITIR UN REPORTE (Mejorado)
**Ruta Admin:** `/admin/reports`

**Funcionalidades:**
- Reporte de asistencias con filtros
- Reporte de horarios por docente
- Reporte de docentes
- Exportación a PDF y HTML

**Uso:**
```php
// Generar reporte de asistencias
POST /admin/reports/attendance
{
    "teacher_id": 1,
    "date_from": "2025-01-01",
    "date_to": "2025-01-31",
    "format": "pdf"
}
```

---

### ✅ CU8: REGISTRAR ACTIVIDAD EN LA BITÁCORA
**Ruta Admin:** `/admin/audit-logs`

**Funcionalidades:**
- Registro automático de todas las acciones CRUD
- Filtros por usuario, acción, modelo, fecha
- Visualización de valores antiguos y nuevos
- Registro de IP y User Agent

**Uso en código:**
```php
use App\Models\AuditLog;

// Registrar acción manualmente
AuditLog::log('created', $model, null, $newValues);
AuditLog::log('updated', $model, $oldValues, $newValues);
AuditLog::log('deleted', $model, $oldValues, null);
```

---

### ✅ CU10: REGISTRAR INCIDENTES DEL AULA
**Ruta Admin:** `/admin/incidents`

**Funcionalidades:**
- CRUD completo de incidentes
- Tipos: daño, mantenimiento, limpieza, otro
- Estados: reportado, en_proceso, resuelto
- Asignación de responsable
- Notas de resolución
- Filtros por aula, estado, tipo, fecha

**Campos del modelo:**
```php
- aula (string)
- incident_date (date)
- type (enum)
- description (text)
- status (enum)
- reported_by (user_id)
- assigned_to (user_id, nullable)
- resolution_notes (text, nullable)
- resolved_at (timestamp, nullable)
```

---

### ✅ CU12: DOCENTE SOLICITA CAMBIO DE HORARIO
**Ruta Teacher:** `/teacher/schedule-change-requests`

**Funcionalidades:**
- Formulario para solicitar cambio de horario
- Selección del horario actual
- Propuesta de nuevo horario (día, hora inicio, hora fin, aula)
- Campo de razón/justificación
- Visualización de solicitudes propias
- Estados: pendiente, aprobado, rechazado

**Flujo:**
1. Docente accede a `/teacher/schedule-change-requests/create`
2. Selecciona el horario que desea cambiar
3. Propone nuevo horario
4. Escribe la razón del cambio
5. Envía la solicitud
6. Puede ver el estado en `/teacher/schedule-change-requests`

---

### ✅ CU13: ADMIN VALIDA LA SOLICITUD DE CAMBIO DE HORARIO
**Ruta Admin:** `/admin/schedule-change-requests`

**Funcionalidades:**
- Lista de solicitudes pendientes
- Visualización detallada de cada solicitud
- Comparación horario actual vs propuesto
- Botones aprobar/rechazar
- Campo de comentarios del admin
- Validación automática de conflictos

**Flujo:**
1. Admin accede a `/admin/schedule-change-requests`
2. Ve lista de solicitudes pendientes
3. Hace clic en una solicitud para ver detalles
4. Aprueba o rechaza con comentarios
5. Si aprueba, el horario se actualiza automáticamente

---

### ✅ CU16: EXPORTAR REPORTES EN PDF
**Ruta Admin:** `/admin/reports`

**Funcionalidades:**
- Exportación de reportes de asistencia a PDF
- Exportación de horarios a PDF
- Exportación de lista de docentes a PDF
- Plantillas personalizables
- Formato profesional

**Tecnología:** Laravel DomPDF

**Uso:**
```php
// En el controlador
$pdf = Pdf::loadView('admin.reports.attendance-pdf', $data);
return $pdf->download('reporte-asistencia.pdf');
```

---

### ✅ CU18: REGISTRA CAMBIO DE HORARIO POR SOLICITUD
**Implementado en:** `ScheduleChangeRequestController::approve()`

**Funcionalidades:**
- Aplicación automática del cambio al aprobar solicitud
- Actualización del registro Schedule
- Creación de registro en ScheduleHistory
- Registro en AuditLog
- Transacción de base de datos para integridad

**Flujo automático:**
1. Admin aprueba solicitud
2. Sistema actualiza el horario
3. Se crea registro en historial
4. Se registra en bitácora
5. Se notifica al docente (pendiente implementar notificaciones)

---

### ✅ CU19: CONSULTAR HISTORIAL DE CAMBIO DE HORARIO
**Ruta Admin:** `/admin/schedule-histories`

**Funcionalidades:**
- Lista completa de cambios de horario
- Filtros por docente, grupo, fecha, tipo de cambio
- Visualización de valores antes/después
- Razón del cambio
- Usuario que realizó el cambio
- Vinculación con solicitud de cambio (si aplica)

**Campos del historial:**
```php
- schedule_id
- changed_by (user_id)
- change_type (created, updated, deleted)
- old_values (JSON)
- new_values (JSON)
- reason
- change_request_id (nullable)
```

---

## 🔐 Permisos y Roles

### Admin
- Acceso completo a todas las funcionalidades
- Gestión de incidentes
- Aprobación/rechazo de solicitudes de cambio
- Visualización de historial y bitácora
- Generación de reportes

### Teacher/Docente
- Solicitud de cambios de horario
- Visualización de sus propias solicitudes
- Registro de asistencia (ya existente)
- Visualización de horarios asignados (ya existente)

---

## 📊 Estructura de Base de Datos

### Tabla: incidents
```sql
id, aula, incident_date, type, description, status, 
reported_by, assigned_to, resolution_notes, resolved_at, 
created_at, updated_at
```

### Tabla: schedule_change_requests
```sql
id, schedule_id, teacher_id, new_day_of_week, new_start_time, 
new_end_time, new_aula, reason, status, reviewed_by, 
admin_comments, reviewed_at, created_at, updated_at
```

### Tabla: schedule_histories
```sql
id, schedule_id, changed_by, change_type, old_values, 
new_values, reason, change_request_id, created_at, updated_at
```

### Tabla: audit_logs
```sql
id, user_id, action, model_type, model_id, old_values, 
new_values, ip_address, user_agent, created_at, updated_at
```

---

## 🎨 Vistas Pendientes

Las siguientes vistas necesitan ser creadas (puedes usar Livewire/Volt o Blade tradicional):

### Admin
- `resources/views/admin/incidents/index.blade.php`
- `resources/views/admin/incidents/create.blade.php`
- `resources/views/admin/incidents/edit.blade.php`
- `resources/views/admin/incidents/show.blade.php`
- `resources/views/admin/schedule-change-requests/index.blade.php`
- `resources/views/admin/schedule-change-requests/show.blade.php`
- `resources/views/admin/schedule-histories/index.blade.php`
- `resources/views/admin/schedule-histories/show.blade.php`
- `resources/views/admin/audit-logs/index.blade.php`
- `resources/views/admin/audit-logs/show.blade.php`
- `resources/views/admin/reports/index.blade.php`
- `resources/views/admin/reports/attendance-pdf.blade.php`
- `resources/views/admin/reports/schedule-pdf.blade.php`
- `resources/views/admin/reports/teacher-pdf.blade.php`

### Teacher
- `resources/views/teacher/schedule-change-requests/index.blade.php`
- `resources/views/teacher/schedule-change-requests/create.blade.php`
- `resources/views/teacher/schedule-change-requests/show.blade.php`

---

## 🧪 Testing

Para probar las nuevas funcionalidades:

### 1. Crear datos de prueba
```bash
php artisan tinker
```

```php
// Crear un incidente
$incident = \App\Models\Incident::create([
    'aula' => 'A-101',
    'incident_date' => now(),
    'type' => 'daño',
    'description' => 'Proyector no funciona',
    'status' => 'reportado',
    'reported_by' => 1,
]);

// Crear una solicitud de cambio
$request = \App\Models\ScheduleChangeRequest::create([
    'schedule_id' => 1,
    'teacher_id' => 2,
    'new_day_of_week' => 'Martes',
    'new_start_time' => '10:00',
    'new_end_time' => '12:00',
    'reason' => 'Conflicto con otra actividad',
    'status' => 'pendiente',
]);
```

### 2. Verificar rutas
```bash
php artisan route:list | grep incidents
php artisan route:list | grep schedule-change
php artisan route:list | grep audit
php artisan route:list | grep reports
```

---

## 🔧 Configuración Adicional

### Configurar DomPDF (opcional)

Publica la configuración:
```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

Edita `config/dompdf.php` para personalizar:
- Tamaño de papel
- Orientación
- Márgenes
- Fuentes

---

## 📝 Mejoras Futuras Sugeridas

1. **Notificaciones**
   - Email cuando se crea/aprueba/rechaza solicitud
   - Notificaciones push en la aplicación
   - Alertas de incidentes críticos

2. **Dashboard mejorado**
   - Gráficos de asistencia
   - Estadísticas de incidentes
   - Solicitudes pendientes en widget

3. **API REST**
   - Endpoints para aplicación móvil
   - Documentación con Swagger/OpenAPI

4. **Validaciones avanzadas**
   - Detección de conflictos de horario más robusta
   - Validación de capacidad de aulas
   - Alertas de sobrecarga de docentes

5. **Exportación adicional**
   - Excel (usando Laravel Excel)
   - CSV
   - Reportes programados automáticos

---

## 🐛 Troubleshooting

### Error: "Class 'Barryvdh\DomPDF\Facade\Pdf' not found"
```bash
composer require barryvdh/laravel-dompdf
php artisan config:clear
```

### Error: "Table doesn't exist"
```bash
php artisan migrate:fresh
# O si tienes datos importantes:
php artisan migrate
```

### Error: "Route not found"
```bash
php artisan route:clear
php artisan route:cache
```

### Error de permisos
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows (ejecutar como administrador)
icacls storage /grant Users:F /t
icacls bootstrap\cache /grant Users:F /t
```

---

## 📞 Soporte

Para dudas o problemas:
1. Revisa el archivo `ANALISIS_CASOS_DE_USO.md`
2. Verifica los logs en `storage/logs/laravel.log`
3. Ejecuta `php artisan route:list` para ver todas las rutas
4. Usa `php artisan tinker` para probar modelos

---

**Fecha:** 13 de noviembre de 2025  
**Versión:** 1.0  
**Autor:** Sistema de Gestión Académica - Grupo 15
