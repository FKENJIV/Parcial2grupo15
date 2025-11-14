@echo off
echo ==========================================
echo Migrando Nuevas Tablas a Supabase
echo ==========================================
echo.

echo Limpiando cache...
call php artisan config:clear
call php artisan cache:clear
echo.

echo Ejecutando migraciones...
call php artisan migrate
echo.

echo ==========================================
echo Migracion completada!
echo ==========================================
echo.
echo Nuevas tablas creadas:
echo - incidents
echo - schedule_change_requests
echo - schedule_histories
echo - audit_logs
echo.
pause
