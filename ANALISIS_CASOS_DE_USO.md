# Análisis de Casos de Uso - Sistema de Gestión Académica

## Proyecto: Sistema Web para Asignación de Horarios, Aulas, Materias, Grupos y Asistencia Docente

---

## RESUMEN EJECUTIVO

### ✅ Casos de Uso IMPLEMENTADOS (11/19)

| CU | Nombre | Estado | Ubicación |
|----|--------|--------|-----------|
| CU1 | Iniciar Sesión | ✅ IMPLEMENTADO | Laravel Fortify (routes/web.php) |
| CU2 | Cerrar Sesión | ✅ IMPLEMENTADO | Laravel Fortify (routes/web.php) |
| CU3 | Ver Horarios Asignados | ✅ IMPLEMENTADO | Teacher\ScheduleController |
| CU4 | Crear Grupo y Asignar Horario | ✅ IMPLEMENTADO | Admin\GroupController |
| CU5 | Registrar Asistencia del Docente | ✅ IMPLEMENTADO | Admin\AttendanceController |
| CU6 | Registrar Nuevo Docente | ✅ IMPLEMENTADO | TeacherController |
| CU9 | Consultar Disponibilidad del Aula | ✅ IMPLEMENTADO | Admin\ScheduleController |
| CU11 | Configurar Roles y Privilegios | ✅ IMPLEMENTADO | Middleware (CheckRole, EnsureUserIsAdmin) |
| CU14 | Registrar Nueva Materia o Grupo | ✅ IMPLEMENTADO | Admin\SubjectController, GroupController |
| CU15 | Consultar Asistencia Histórica | ✅ IMPLEMENTADO | Admin\AttendanceController (filtros) |
| CU17 | Ver Aulas Asignadas por Día | ✅ IMPLEMENTADO | Admin\ScheduleController |

### ⚠️ Casos de Uso PARCIALMENTE IMPLEMENTADOS (2/19)

| CU | Nombre | Estado | Faltante |
|----|--------|--------|----------|
| CU7 | Emitir un Reporte | ⚠️ PARCIAL | Falta exportación PDF y reportes específicos |
| CU8 | Registrar Actividad en la Bitácora | ⚠️ PARCIAL | Falta modelo de auditoría/logs |

### ❌ Casos de Uso NO IMPLEMENTADOS (6/19)

| CU | Nombre | Prioridad |
|----|--------|-----------|
| CU10 | Registrar Incidentes del Aula | ALTA |
| CU12 | Docente Solicita Cambio de Horario | ALTA |
| CU13 | Admin Valida Solicitud de Cambio | ALTA |
| CU16 | Exportar Reportes en PDF | MEDIA |
| CU18 | Registra Cambio de Horario por Solicitud | ALTA |
| CU19 | Consultar Historial de Cambio de Horario | MEDIA |

---

## DETALLE DE CASOS DE USO

### ✅ CU1: INICIAR SESIÓN
**Estado:** IMPLEMENTADO
**Tecnología:** Laravel Fortify
**Archivos:**
- `app/Providers/FortifyServiceProvider.php`
- `routes/web.php` (rutas de autenticación)
- Vistas Livewire en `resources/views/livewire/auth/`

**Funcionalidades:**
- Login con email y contraseña
- Autenticación de dos factores (2FA) opcional
- Redirección basada en roles (admin → dashboard, teacher → teacher.dashboard)
- Protección CSRF

---

### ✅ CU2: CERRAR SESIÓN
**Estado:** IMPLEMENTADO
**Tecnología:** Laravel Fortify
**Archivos:**
- Rutas de logout manejadas por Fortify
- Middleware de autenticación

**Funcionalidades:**
- Logout seguro con invalidación de sesión
- Redirección a página de login

---

### ✅ CU3: VER HORARIOS ASIGNADOS
**Estado:** IMPLEMENTADO
**Controlador:** `app/Http/Controllers/Teacher/ScheduleController.php`
**Ruta:** `GET /teacher/schedules`
**Middleware:** `auth, verified, teacher`

**Funcionalidades:**
- Docentes pueden ver sus horarios asignados
- Filtrado por día de la semana
- Visualización de aula, materia, grupo
- Horarios de inicio y fin

---

### ✅ CU4: CREAR GRUPO Y ASIGNAR HORARIO
**Estado:** IMPLEMENTADO
**Controlador:** `app/Http/Controllers/Admin/GroupController.php`
**Rutas:**
- `GET /admin/groups/create`
- `POST /admin/groups`
**Middleware:** `auth, verified, admin`

**Funcionalidades:**
- Crear grupos académicos
- Asignar docente al grupo
- Asignar materia (subject_id)
- Crear múltiples horarios por grupo
- Validación de conflictos de horario
- Asignación de aulas

**Modelo de datos:**
```php
Group: id, name, subject_id, teacher_id, capacity
Schedule: id, group_id, day_of_week, start_time, end_time, aula
```

---

### ✅ CU5: REGISTRAR ASISTENCIA DEL DOCENTE
**Estado:** IMPLEMENTADO
**Controlador:** `app/Http/Controllers/Admin/AttendanceController.php`
**Rutas:**
- `GET /admin/attendance/create`
- `POST /admin/attendance`
**Middleware:** `auth, verified, admin`

**Funcionalidades:**
- Registro de asistencia (presente, ausente, tarde)
- Asociación con horario específico
- Registro de fecha y hora
- Notas adicionales
- Validación de duplicados

**Modelo de datos:**
```php
Attendance: id, teacher_id, schedule_id, group_id, status, notes, aula, registered_at
```

---

### ✅ CU6: REGISTRAR NUEVO DOCENTE
**Estado:** IMPLEMENTADO
**Controlador:** `app/Http/Controllers/TeacherController.php`
**Rutas:**
- `GET /docentes/create`
- `POST /docentes`
**Middleware:** `auth, verified, admin`

**Funcionalidades:**
- Registro completo de docentes
- Campos: nombre, email, código, tipo (titular/invitado/auxiliar)
- Teléfono, estado (activo/inactivo)
- Especialidades/materias
- Asignación de contraseña
- Validación de unicidad (email, código)

**Modelo de datos:**
```php
User: id, name, email, code, role, type, phone, status, specialties, password
```

---

### ⚠️ CU7: EMITIR UN REPORTE
**Estado:** PARCIALMENTE IMPLEMENTADO
**Archivos existentes:**
- `app/Http/Controllers/Admin/AttendanceController.php` (filtros)

**Funcionalidades existentes:**
- Filtrado de asistencias por docente, grupo, fecha, estado
- Paginación de resultados

**Faltante:**
- Generación de reportes en PDF
- Reportes de horarios por docente
- Reportes de ocupación de aulas
- Reportes estadísticos de asistencia
- Exportación a Excel

---

### ⚠️ CU8: REGISTRAR ACTIVIDAD EN LA BITÁCORA
**Estado:** PARCIALMENTE IMPLEMENTADO
**Tecnología:** Laravel Log (básico)

**Funcionalidades existentes:**
- Logs de aplicación en `storage/logs/laravel.log`
- Logs de errores automáticos

**Faltante:**
- Modelo de auditoría específico
- Registro de acciones de usuarios (CRUD)
- Interfaz para consultar bitácora
- Filtros por usuario, acción, fecha
- Retención de logs históricos

---

### ✅ CU9: CONSULTAR DISPONIBILIDAD DEL AULA
**Estado:** IMPLEMENTADO
**Controlador:** `app/Http/Controllers/Admin/ScheduleController.php`
**Funcionalidades:**
- Consulta de horarios por aula
- Visualización de ocupación
- Detección de conflictos

---

### ❌ CU10: REGISTRAR INCIDENTES DEL AULA
**Estado:** NO IMPLEMENTADO
**Prioridad:** ALTA

**Funcionalidades requeridas:**
- Modelo `Incident` (aula, fecha, tipo, descripción, estado)
- Controlador para CRUD de incidentes
- Tipos: daño, mantenimiento, limpieza, otro
- Estados: reportado, en proceso, resuelto
- Asignación de responsable
- Notificaciones

---

### ✅ CU11: CONFIGURAR ROLES Y PRIVILEGIOS
**Estado:** IMPLEMENTADO
**Archivos:**
- `app/Http/Middleware/EnsureUserIsAdmin.php`
- `app/Http/Middleware/EnsureUserIsTeacher.php`
- `app/Http/Middleware/CheckRole.php`

**Funcionalidades:**
- Roles: admin, teacher/docente
- Middleware de protección de rutas
- Verificación de permisos

**Mejoras sugeridas:**
- Sistema de permisos granular (Spatie Permission)
- Roles adicionales (coordinador, secretaria)

---

### ❌ CU12: DOCENTE SOLICITA CAMBIO DE HORARIO
**Estado:** NO IMPLEMENTADO
**Prioridad:** ALTA

**Funcionalidades requeridas:**
- Modelo `ScheduleChangeRequest`
- Formulario para docente
- Campos: schedule_id, nuevo horario propuesto, razón
- Estados: pendiente, aprobado, rechazado
- Notificación al admin

---

### ❌ CU13: ADMIN VALIDA LA SOLICITUD DE CAMBIO DE HORARIO
**Estado:** NO IMPLEMENTADO
**Prioridad:** ALTA

**Funcionalidades requeridas:**
- Vista de solicitudes pendientes
- Botones aprobar/rechazar
- Comentarios del admin
- Validación de conflictos
- Notificación al docente

---

### ✅ CU14: REGISTRAR NUEVA MATERIA O GRUPO ACADÉMICO
**Estado:** IMPLEMENTADO
**Controladores:**
- `app/Http/Controllers/Admin/SubjectController.php`
- `app/Http/Controllers/Admin/GroupController.php`

**Funcionalidades:**
- CRUD completo de materias
- CRUD completo de grupos
- Activación/desactivación de materias
- Asignación de créditos

---

### ✅ CU15: CONSULTAR ASISTENCIA HISTÓRICA
**Estado:** IMPLEMENTADO
**Controlador:** `app/Http/Controllers/Admin/AttendanceController.php`
**Ruta:** `GET /admin/attendance`

**Funcionalidades:**
- Filtros por docente, grupo, rango de fechas, estado
- Paginación
- Visualización detallada
- Ordenamiento por fecha

---

### ❌ CU16: EXPORTAR REPORTES EN PDF
**Estado:** NO IMPLEMENTADO
**Prioridad:** MEDIA

**Funcionalidades requeridas:**
- Librería: DomPDF o Laravel Snappy
- Exportar asistencias a PDF
- Exportar horarios a PDF
- Exportar lista de docentes a PDF
- Plantillas personalizadas
- Logo institucional

---

### ✅ CU17: VER AULAS ASIGNADAS POR DÍA
**Estado:** IMPLEMENTADO
**Controlador:** `app/Http/Controllers/Admin/ScheduleController.php`
**Funcionalidades:**
- Vista de horarios por día
- Filtrado por aula
- Visualización de ocupación

---

### ❌ CU18: REGISTRA CAMBIO DE HORARIO POR SOLICITUD
**Estado:** NO IMPLEMENTADO
**Prioridad:** ALTA
**Dependencia:** CU12, CU13

**Funcionalidades requeridas:**
- Aplicar cambio aprobado
- Actualizar Schedule
- Registrar en historial
- Notificar a docente

---

### ❌ CU19: CONSULTAR HISTORIAL DE CAMBIO DE HORARIO
**Estado:** NO IMPLEMENTADO
**Prioridad:** MEDIA

**Funcionalidades requeridas:**
- Modelo `ScheduleHistory`
- Registro de cambios (antes/después)
- Filtros por docente, grupo, fecha
- Razón del cambio
- Usuario que aprobó

---

## ARQUITECTURA ACTUAL

### Modelos
- ✅ User (docentes y admins)
- ✅ Group (grupos académicos)
- ✅ Subject (materias)
- ✅ Schedule (horarios)
- ✅ Attendance (asistencias)
- ❌ Incident (incidentes de aula)
- ❌ ScheduleChangeRequest (solicitudes de cambio)
- ❌ ScheduleHistory (historial de cambios)
- ❌ AuditLog (bitácora de auditoría)

### Controladores
- ✅ Admin\AttendanceController
- ✅ Admin\GroupController
- ✅ Admin\ScheduleController
- ✅ Admin\SubjectController
- ✅ TeacherController
- ✅ Teacher\ScheduleController
- ✅ Teacher\AttendanceController
- ❌ Admin\IncidentController
- ❌ Admin\ScheduleChangeRequestController
- ❌ Admin\ReportController

### Middleware
- ✅ EnsureUserIsAdmin
- ✅ EnsureUserIsTeacher
- ✅ CheckRole

---

## RECOMENDACIONES

### Prioridad Alta
1. Implementar sistema de solicitudes de cambio de horario (CU12, CU13, CU18)
2. Implementar registro de incidentes de aula (CU10)
3. Completar sistema de bitácora de auditoría (CU8)

### Prioridad Media
4. Implementar exportación de reportes en PDF (CU16)
5. Implementar historial de cambios de horario (CU19)
6. Mejorar sistema de reportes (CU7)

### Mejoras Técnicas
- Implementar sistema de notificaciones (email/push)
- Agregar validación de conflictos de horarios más robusta
- Implementar caché para consultas frecuentes
- Agregar tests automatizados
- Documentación API REST (ya existe parcialmente)

---

## TECNOLOGÍAS UTILIZADAS

- **Framework:** Laravel 12
- **Frontend:** Livewire + Volt + Flux UI
- **Autenticación:** Laravel Fortify
- **Base de datos:** PostgreSQL (producción), SQLite (desarrollo)
- **Despliegue:** Docker + Render.com

---

**Fecha de análisis:** 13 de noviembre de 2025
**Versión del documento:** 1.0
