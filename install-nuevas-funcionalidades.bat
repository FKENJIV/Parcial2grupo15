@echo off
echo ==========================================
echo Instalacion de Nuevas Funcionalidades
echo Sistema de Gestion Academica
echo ==========================================
echo.

REM Verificar que estamos en un proyecto Laravel
if not exist "artisan" (
    echo Error: No se encontro el archivo artisan. Asegurate de estar en la raiz del proyecto Laravel.
    exit /b 1
)

echo Proyecto Laravel detectado
echo.

REM Instalar dependencia para PDF
echo Instalando barryvdh/laravel-dompdf para generacion de PDFs...
call composer require barryvdh/laravel-dompdf
echo.

REM Ejecutar migraciones
echo Ejecutando migraciones de base de datos...
call php artisan migrate
echo.

REM Limpiar cache
echo Limpiando cache de Laravel...
call php artisan config:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan view:clear
echo.

REM Optimizar aplicacion
echo Optimizando aplicacion...
call php artisan config:cache
call php artisan route:cache
echo.

echo ==========================================
echo Instalacion completada exitosamente
echo ==========================================
echo.
echo Nuevas funcionalidades implementadas:
echo.
echo CU7  - Emitir reportes (mejorado)
echo CU8  - Registrar actividad en bitacora
echo CU10 - Registrar incidentes del aula
echo CU12 - Docente solicita cambio de horario
echo CU13 - Admin valida solicitud de cambio
echo CU16 - Exportar reportes en PDF
echo CU18 - Registra cambio de horario por solicitud
echo CU19 - Consultar historial de cambio de horario
echo.
echo Rutas disponibles:
echo.
echo Admin:
echo   - /admin/incidents
echo   - /admin/schedule-change-requests
echo   - /admin/schedule-histories
echo   - /admin/audit-logs
echo   - /admin/reports
echo.
echo Teacher:
echo   - /teacher/schedule-change-requests
echo.
echo Para ver todas las rutas: php artisan route:list
echo.
pause
