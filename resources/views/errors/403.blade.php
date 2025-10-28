<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso Denegado</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 to-white flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center border-2 border-indigo-100">
            <!-- Icon -->
            <div class="mx-auto h-20 w-20 bg-red-100 rounded-full flex items-center justify-center mb-6">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-3">Acceso Denegado</h1>
            
            <!-- Message -->
            <p class="text-gray-600 mb-6">
                {{ $exception->getMessage() ?: 'No tienes permiso para acceder a esta sección.' }}
            </p>

            <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200 mb-6">
                <p class="text-sm text-indigo-700">
                    <strong>Error 403:</strong> Permisos insuficientes
                </p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <a href="javascript:history.back()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
                <a href="{{ route('home') }}" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Ir al Inicio
                </a>
            </div>
        </div>

        <!-- Help Text -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Si crees que esto es un error, contacta al administrador del sistema.
        </p>
    </div>
</body>
</html>
