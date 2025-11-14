<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Symfony\Component\HttpFoundation\Response;

class AuditMiddleware
{
    /**
     * Flag to prevent recursive logging
     */
    private static $isLogging = false;

    /**
     * Handle an incoming request and log it to audit_logs
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo registrar si el usuario está autenticado y no estamos ya registrando
        if (auth()->check() && !self::$isLogging) {
            $this->logRequest($request, $response);
        }

        return $response;
    }

    /**
     * Log the request to audit_logs table
     */
    protected function logRequest(Request $request, Response $response): void
    {
        try {
            // Determinar la acción basada en la ruta y método
            $action = $this->determineAction($request);
            
            // Solo registrar acciones importantes (no assets, no livewire polling)
            if ($this->shouldLog($request, $action)) {
                // Set flag to prevent recursive logging
                self::$isLogging = true;
                
                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => $action,
                    'model_type' => $this->getModelType($request),
                    'model_id' => $this->getModelId($request),
                    'old_values' => null,
                    'new_values' => $this->getRequestData($request),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                
                // Reset flag after logging
                self::$isLogging = false;
            }
        } catch (\Exception $e) {
            // Reset flag on error
            self::$isLogging = false;
            // No fallar si hay error en el logging
            \Log::error('Error en AuditMiddleware: ' . $e->getMessage());
        }
    }

    /**
     * Determine the action name based on route and method
     */
    protected function determineAction(Request $request): string
    {
        $method = $request->method();
        $path = $request->path();
        $routeName = $request->route()?->getName();

        // Acciones especiales
        if ($path === 'login' && $method === 'POST') {
            return 'login';
        }
        if ($path === 'logout' && $method === 'POST') {
            return 'logout';
        }

        // Basado en el método HTTP
        $actionMap = [
            'GET' => 'viewed',
            'POST' => 'created',
            'PUT' => 'updated',
            'PATCH' => 'updated',
            'DELETE' => 'deleted',
        ];

        $baseAction = $actionMap[$method] ?? 'accessed';

        // Agregar contexto de la ruta
        if ($routeName) {
            return $baseAction . '_' . str_replace('.', '_', $routeName);
        }

        // Usar el path como fallback
        $pathParts = explode('/', trim($path, '/'));
        $module = $pathParts[0] ?? 'unknown';
        
        return $baseAction . '_' . $module;
    }

    /**
     * Get the model type from the request
     */
    protected function getModelType(Request $request): ?string
    {
        $path = $request->path();
        
        $modelMap = [
            'docentes' => 'App\\Models\\User',
            'admin/groups' => 'App\\Models\\Group',
            'admin/schedules' => 'App\\Models\\Schedule',
            'admin/attendance' => 'App\\Models\\Attendance',
            'admin/subjects' => 'App\\Models\\Subject',
            'admin/incidents' => 'App\\Models\\Incident',
            'admin/schedule-change-requests' => 'App\\Models\\ScheduleChangeRequest',
            'admin/schedule-histories' => 'App\\Models\\ScheduleHistory',
        ];

        foreach ($modelMap as $pathPattern => $model) {
            if (str_contains($path, $pathPattern)) {
                return $model;
            }
        }

        return null;
    }

    /**
     * Get the model ID from the request
     */
    protected function getModelId(Request $request): ?int
    {
        // Intentar obtener el ID de los parámetros de ruta
        $routeParams = $request->route()?->parameters() ?? [];
        
        foreach ($routeParams as $param) {
            if (is_numeric($param)) {
                return (int) $param;
            }
            if (is_object($param) && method_exists($param, 'getKey')) {
                return $param->getKey();
            }
        }

        return null;
    }

    /**
     * Get relevant request data (excluding sensitive info)
     */
    protected function getRequestData(Request $request): ?array
    {
        if ($request->method() === 'GET') {
            return null; // No guardar query params en GET
        }

        $data = $request->except([
            'password',
            'password_confirmation',
            '_token',
            '_method',
        ]);

        return empty($data) ? null : $data;
    }

    /**
     * Determine if this request should be logged
     */
    protected function shouldLog(Request $request, string $action): bool
    {
        $path = $request->path();

        // No registrar assets, livewire, etc.
        $skipPatterns = [
            'livewire/',
            'build/',
            'favicon',
            '.css',
            '.js',
            '.svg',
            '.ico',
            '.png',
            '.jpg',
            'flux/',
            '.well-known/',
            'audit-logs', // Evitar bucle infinito al registrar audit logs
        ];

        foreach ($skipPatterns as $pattern) {
            if (str_contains($path, $pattern)) {
                return false;
            }
        }

        // No registrar accesos simples a dashboard (solo GET)
        if ($request->method() === 'GET' && in_array($path, ['dashboard', 'teacher/dashboard', '/'])) {
            return false;
        }

        return true;
    }
}
