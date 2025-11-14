@echo off
echo ==========================================
echo Iniciando Servidor Laravel en Puerto 8001
echo ==========================================
echo.
echo Accede a: http://127.0.0.1:8001
echo.
echo Credenciales:
echo Admin: admin@example.com / password
echo Docente: prueba@correo.com / password
echo.
echo Presiona Ctrl+C para detener el servidor
echo ==========================================
echo.
php artisan serve --port=8001
