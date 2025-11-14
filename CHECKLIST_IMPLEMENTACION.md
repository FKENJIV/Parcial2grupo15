# ✅ Checklist de Implementación

## Sistema de Gestión Académica - Nuevas Funcionalidades

---

## 📋 Pre-requisitos

- [ ] PHP 8.2 o superior instalado
- [ ] Composer instalado
- [ ] Laravel 12 funcionando
- [ ] Base de datos PostgreSQL/MySQL configurada
- [ ] Acceso a terminal/línea de comandos

---

## 🚀 Instalación

### Paso 1: Instalar Dependencias
- [ ] Ejecutar `composer require barryvdh/laravel-dompdf`
- [ ] Verificar que se instaló correctamente

### Paso 2: Ejecutar Migraciones
- [ ] Ejecutar `php artisan migrate`
- [ ] Verificar que se crearon las 4 nuevas tablas:
  - [ ] `incidents`
  - [ ] `schedule_change_requests`
  - [ ] `schedule_histories`
  - [ ] `audit_logs`

### Paso 3: Limpiar Caché
- [ ] `php artisan config:clear`
- [ ] `php artisan cache:clear`
- [ ] `php artisan route:clear`
- [ ] `php artisan view:clear`

### Paso 4: Optimizar
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`

### Paso 5: Verificar Instalación
- [ ] Ejecutar `php verificar-instalacion.php`
- [ ] Revisar que no haya errores
- [ ] Verificar que todas las rutas estén disponibles: `php artisan route:list`

---

## 📁 Archivos Creados - Verificación

### Migraciones (4 archivos)
- [ ] `database/migrations/2025_11_13_000001_create_incidents_table.php`
- [ ] `database/migrations/2025_11_13_000002_create_schedule_change_requests_table.php`
- [ ] `database/migrations/2025_11_13_000003_create_schedule_histories_table.php`
- [ ] `database/migrations/2025_11_13_000004_create_audit_logs_table.php`

### Modelos (4 archivos)
- [ ] `app/Models/Incident.php`
- [ ] `app/Models/ScheduleChangeRequest.php`
- [ ] `app/Models/ScheduleHistory.php`
- [ ] `app/Models/AuditLog.php`

### Controladores Admin (5 archivos)
- [ ] `app/Http/Controllers/Admin/IncidentController.php`
- [ ] `app/Http/Controllers/Admin/ScheduleChangeRequestController.php`
- [ ] `app/Http/Controllers/Admin/ScheduleHistoryController.php`
- [ ] `app/Http/Controllers/Admin/AuditLogController.php`
- [ ] `app/Http/Controllers/Admin/ReportController.php`

### Controladores Teacher (1 archivo)
- [ ] `app/Http/Controllers/Teacher/ScheduleChangeRequestController.php`

### Configuración (1 archivo)
- [ ] `config/dompdf.php`

### Rutas
- [ ] Verificar que `routes/web.php` tiene las nuevas rutas

### Documentación (5 archivos)
- [ ] `ANALISIS_CASOS_DE_USO.md`
- [ ] `INSTRUCCIONES_IMPLEMENTACION.md`
- [ ] `RESUMEN_EJECUTIVO.md`
- [ ] `EJEMPLOS_DE_USO.md`
- [ ] `CHECKLIST_IMPLEMENTACION.md` (este archivo)

### Scripts (3 archivos)
- [ ] `install-nuevas-funcionalidades.sh`
- [ ] `install-nuevas-funcionalidades.bat`
- [ ] `verificar-instalacion.php`

---

## 🎨 Vistas a Crear (Pendiente)

### Admin - Incidentes
- [ ] `resources/views/admin/incidents/index.blade.php`
- [ ] `resources/views/admin/incidents/create.blade.php`
- [ ] `resources/views/admin/incidents/edit.blade.php`
- [ ] `resources/views/admin/incidents/show.blade.php`

### Admin - Solicitudes de Cambio
- [ ] `resources/views/admin/schedule-change-requests/index.blade.php`
- [ ] `resources/views/admin/schedule-change-requests/show.blade.php`

### Admin - Historial
- [ ] `resources/views/admin/schedule-histories/index.blade.php`
- [ ] `resources/views/admin/schedule-histories/show.blade.php`

### Admin - Bitácora
- [ ] `resources/views/admin/audit-logs/index.blade.php`
- [ ] `resources/views/admin/audit-logs/show.blade.php`

### Admin - Reportes
- [ ] `resources/views/admin/reports/index.blade.php`
- [ ] `resources/views/admin/reports/attendance-pdf.blade.php`
- [ ] `resources/views/admin/reports/attendance-html.blade.php`
- [ ] `resources/views/admin/reports/schedule-pdf.blade.php`
- [ ] `resources/views/admin/reports/schedule-html.blade.php`
- [ ] `resources/views/admin/reports/teacher-pdf.blade.php`
- [ ] `resources/views/admin/reports/teacher-html.blade.php`

### Teacher - Solicitudes
- [ ] `resources/views/teacher/schedule-change-requests/index.blade.php`
- [ ] `resources/views/teacher/schedule-change-requests/create.blade.php`
- [ ] `resources/views/teacher/schedule-change-requests/show.blade.php`

---

## 🧪 Testing

### Pruebas Manuales

#### CU10: Incidentes
- [ ] Acceder a `/admin/incidents`
- [ ] Crear un nuevo incidente
- [ ] Editar un incidente
- [ ] Cambiar estado a "en_proceso"
- [ ] Resolver un incidente
- [ ] Filtrar por aula
- [ ] Filtrar por estado
- [ ] Eliminar un incidente

#### CU12: Solicitud de Cambio (Docente)
- [ ] Login como docente
- [ ] Acceder a `/teacher/schedule-change-requests`
- [ ] Crear nueva solicitud
- [ ] Ver lista de solicitudes propias
- [ ] Ver detalle de una solicitud

#### CU13: Validar Solicitud (Admin)
- [ ] Login como admin
- [ ] Acceder a `/admin/schedule-change-requests`
- [ ] Ver solicitudes pendientes
- [ ] Aprobar una solicitud
- [ ] Verificar que el horario se actualizó
- [ ] Rechazar una solicitud con comentarios

#### CU16: Reportes PDF
- [ ] Acceder a `/admin/reports`
- [ ] Generar reporte de asistencias en PDF
- [ ] Generar reporte de horarios en PDF
- [ ] Generar reporte de docentes en PDF
- [ ] Verificar que los PDFs se descargan correctamente

#### CU19: Historial
- [ ] Acceder a `/admin/schedule-histories`
- [ ] Ver historial de cambios
- [ ] Filtrar por docente
- [ ] Filtrar por fecha
- [ ] Ver detalle de un cambio

#### CU8: Bitácora
- [ ] Acceder a `/admin/audit-logs`
- [ ] Ver registros de auditoría
- [ ] Filtrar por usuario
- [ ] Filtrar por acción
- [ ] Filtrar por fecha

### Pruebas con Tinker

```bash
php artisan tinker
```

- [ ] Crear incidente de prueba
```php
$incident = \App\Models\Incident::create([
    'aula' => 'TEST-101',
    'incident_date' => now(),
    'type' => 'daño',
    'description' => 'Test',
    'status' => 'reportado',
    'reported_by' => 1,
]);
```

- [ ] Crear solicitud de cambio de prueba
```php
$request = \App\Models\ScheduleChangeRequest::create([
    'schedule_id' => 1,
    'teacher_id' => 2,
    'new_day_of_week' => 'Miércoles',
    'new_start_time' => '10:00',
    'new_end_time' => '12:00',
    'reason' => 'Test',
    'status' => 'pendiente',
]);
```

- [ ] Verificar registro en bitácora
```php
\App\Models\AuditLog::latest()->first();
```

---

## 🔧 Configuración Adicional

### Permisos de Archivos (Linux/Mac)
- [ ] `chmod -R 775 storage`
- [ ] `chmod -R 775 bootstrap/cache`
- [ ] `chown -R www-data:www-data storage bootstrap/cache`

### Variables de Entorno
- [ ] Verificar `DB_CONNECTION` en `.env`
- [ ] Verificar `DB_DATABASE` en `.env`
- [ ] Verificar `DB_USERNAME` en `.env`
- [ ] Verificar `DB_PASSWORD` en `.env`

### Configuración de DomPDF
- [ ] Crear directorio `storage/fonts` si no existe
- [ ] Dar permisos de escritura a `storage/fonts`

---

## 📊 Verificación de Base de Datos

### Verificar Tablas
```sql
-- PostgreSQL
SELECT table_name FROM information_schema.tables 
WHERE table_schema = 'public' 
AND table_name IN ('incidents', 'schedule_change_requests', 'schedule_histories', 'audit_logs');

-- MySQL
SHOW TABLES LIKE '%incident%';
SHOW TABLES LIKE '%schedule_change%';
SHOW TABLES LIKE '%audit%';
```

- [ ] Tabla `incidents` existe
- [ ] Tabla `schedule_change_requests` existe
- [ ] Tabla `schedule_histories` existe
- [ ] Tabla `audit_logs` existe

### Verificar Estructura
```sql
-- Ver estructura de tabla
DESC incidents;
DESC schedule_change_requests;
DESC schedule_histories;
DESC audit_logs;
```

- [ ] Todas las columnas están presentes
- [ ] Los índices están creados
- [ ] Las foreign keys están configuradas

---

## 🔐 Seguridad

- [ ] Verificar que solo admins pueden acceder a rutas admin
- [ ] Verificar que docentes solo ven sus propias solicitudes
- [ ] Verificar que las rutas están protegidas con middleware
- [ ] Verificar que se registran las acciones en bitácora
- [ ] Verificar que se validan los datos de entrada

---

## 📱 Navegación

### Menú Admin (Agregar enlaces)
- [ ] Enlace a "Incidentes" → `/admin/incidents`
- [ ] Enlace a "Solicitudes de Cambio" → `/admin/schedule-change-requests`
- [ ] Enlace a "Historial de Cambios" → `/admin/schedule-histories`
- [ ] Enlace a "Bitácora" → `/admin/audit-logs`
- [ ] Enlace a "Reportes" → `/admin/reports`

### Menú Teacher (Agregar enlaces)
- [ ] Enlace a "Mis Solicitudes" → `/teacher/schedule-change-requests`
- [ ] Enlace a "Nueva Solicitud" → `/teacher/schedule-change-requests/create`

---

## 📈 Métricas de Éxito

### Funcionalidad
- [ ] Todos los casos de uso funcionan correctamente
- [ ] No hay errores en los logs
- [ ] Las transacciones de BD funcionan correctamente
- [ ] Los PDFs se generan correctamente

### Performance
- [ ] Las consultas son eficientes (usar eager loading)
- [ ] No hay queries N+1
- [ ] Los reportes se generan en tiempo razonable

### Usabilidad
- [ ] Las interfaces son intuitivas
- [ ] Los mensajes de error son claros
- [ ] Los mensajes de éxito son informativos
- [ ] La navegación es fluida

---

## 🐛 Troubleshooting

### Si hay errores de migración:
- [ ] Verificar conexión a BD
- [ ] Verificar permisos de usuario de BD
- [ ] Ejecutar `php artisan migrate:status`
- [ ] Si es necesario: `php artisan migrate:fresh` (¡cuidado con datos!)

### Si hay errores de rutas:
- [ ] Ejecutar `php artisan route:clear`
- [ ] Ejecutar `php artisan route:cache`
- [ ] Verificar `routes/web.php`

### Si hay errores de PDF:
- [ ] Verificar que DomPDF está instalado
- [ ] Verificar permisos en `storage/fonts`
- [ ] Revisar `config/dompdf.php`

### Si hay errores de permisos:
- [ ] Verificar middleware en rutas
- [ ] Verificar rol del usuario
- [ ] Revisar logs en `storage/logs/laravel.log`

---

## 📚 Documentación

- [ ] Leer `ANALISIS_CASOS_DE_USO.md`
- [ ] Leer `INSTRUCCIONES_IMPLEMENTACION.md`
- [ ] Leer `RESUMEN_EJECUTIVO.md`
- [ ] Revisar `EJEMPLOS_DE_USO.md`
- [ ] Compartir documentación con el equipo

---

## ✅ Finalización

- [ ] Todas las funcionalidades están implementadas
- [ ] Todas las pruebas pasan
- [ ] La documentación está completa
- [ ] El código está en el repositorio
- [ ] El equipo está capacitado
- [ ] El sistema está listo para producción

---

## 📝 Notas Adicionales

### Fecha de inicio: _______________
### Fecha de finalización: _______________
### Responsable: _______________

### Problemas encontrados:
```
1. 
2. 
3. 
```

### Soluciones aplicadas:
```
1. 
2. 
3. 
```

---

**Última actualización:** 13 de noviembre de 2025  
**Versión:** 1.0
