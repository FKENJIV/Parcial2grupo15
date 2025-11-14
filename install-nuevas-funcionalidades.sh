#!/bin/bash

echo "=========================================="
echo "Instalación de Nuevas Funcionalidades"
echo "Sistema de Gestión Académica"
echo "=========================================="
echo ""

# Verificar que estamos en un proyecto Laravel
if [ ! -f "artisan" ]; then
    echo "❌ Error: No se encontró el archivo artisan. Asegúrate de estar en la raíz del proyecto Laravel."
    exit 1
fi

echo "✅ Proyecto Laravel detectado"
echo ""

# Instalar dependencia para PDF
echo "📦 Instalando barryvdh/laravel-dompdf para generación de PDFs..."
composer require barryvdh/laravel-dompdf
echo ""

# Ejecutar migraciones
echo "🗄️  Ejecutando migraciones de base de datos..."
php artisan migrate
echo ""

# Limpiar caché
echo "🧹 Limpiando caché de Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo ""

# Optimizar aplicación
echo "⚡ Optimizando aplicación..."
php artisan config:cache
php artisan route:cache
echo ""

echo "=========================================="
echo "✅ Instalación completada exitosamente"
echo "=========================================="
echo ""
echo "Nuevas funcionalidades implementadas:"
echo ""
echo "✅ CU7  - Emitir reportes (mejorado)"
echo "✅ CU8  - Registrar actividad en bitácora"
echo "✅ CU10 - Registrar incidentes del aula"
echo "✅ CU12 - Docente solicita cambio de horario"
echo "✅ CU13 - Admin valida solicitud de cambio"
echo "✅ CU16 - Exportar reportes en PDF"
echo "✅ CU18 - Registra cambio de horario por solicitud"
echo "✅ CU19 - Consultar historial de cambio de horario"
echo ""
echo "Rutas disponibles:"
echo ""
echo "Admin:"
echo "  - /admin/incidents"
echo "  - /admin/schedule-change-requests"
echo "  - /admin/schedule-histories"
echo "  - /admin/audit-logs"
echo "  - /admin/reports"
echo ""
echo "Teacher:"
echo "  - /teacher/schedule-change-requests"
echo ""
echo "Para ver todas las rutas: php artisan route:list"
echo ""
