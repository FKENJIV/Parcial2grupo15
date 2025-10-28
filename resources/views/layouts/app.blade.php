<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-partials.head />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex">
        <!-- SIDEBAR -->
        <aside class="w-72 bg-white border-r border-gray-200 flex-shrink-0 flex flex-col">
            <div class="px-6 py-6">
                <h1 class="text-2xl font-extrabold text-indigo-600">SIS - Horarios</h1>
            </div>

            <nav class="px-4 space-y-1 flex-1">
                <a class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg' : '' }}" href="{{ route('dashboard') }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-500' }}" viewBox="0 0 24 24" fill="none">
                        <path d="M3 12h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Dashboard
                </a>

                <a class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('horarios') ? 'bg-indigo-600 text-white shadow-lg' : '' }}" href="{{ route('horarios') }}">
                    Ver Horarios
                </a>

                <a class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('administrar.carga') ? 'bg-indigo-600 text-white shadow-lg' : '' }}" href="{{ route('administrar.carga') }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin-load.index') ? 'text-white opacity-90' : 'text-gray-500' }}" viewBox="0 0 24 24" fill="none">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Administrar Carga
                </a>

                <a class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('registro.asistencia') ? 'bg-indigo-600 text-white shadow-lg' : '' }}" href="{{ route('registro.asistencia') }}">
                    Registro Asistencia
                </a>

                <a class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-50 {{ request()->routeIs('docentes.index') ? 'bg-indigo-600 text-white shadow-lg' : '' }}" href="{{ route('docentes.index') }}">
                    Docentes
                </a>
            </nav>

            <div class="p-4 mt-auto">
                <div class="bg-indigo-50 rounded-lg p-3">
                    <div class="text-sm font-medium text-indigo-800">
                        {{ auth()->user()->name ?? 'Usuario' }}
                    </div>
                    <div class="text-xs text-indigo-600">
                        Rol: {{ auth()->user()->role ?? 'DOCENTE' }}
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full py-2 rounded-lg border border-indigo-100 text-indigo-700 hover:bg-indigo-50">
                        CERRAR SESIÓN
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    @vite(['resources/js/app.js'])
</body>
</html>