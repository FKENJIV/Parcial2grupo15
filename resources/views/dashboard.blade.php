<x-layouts.app :title="__('Dashboard')">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold">Dashboard</h2>
        <p class="text-gray-600 mt-1">Vista general del sistema de gestión académica</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Teachers -->
        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 shadow-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Docentes</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTeachers ?? 0 }}</p>
                </div>
            </div>
        </section>

        <!-- Active Groups -->
        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center gap-4">
                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 shadow-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Grupos Activos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $activeGroups ?? 0 }}</p>
                </div>
            </div>
        </section>

        <!-- Today's Attendance -->
        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center gap-4">
                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 shadow-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Asistencias Hoy</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $todayAttendance ?? 0 }}</p>
                </div>
            </div>
        </section>

        <!-- Pending Tasks -->
        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center gap-4">
                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 shadow-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tareas Pendientes</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingTasks ?? 0 }}</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 shadow-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg text-gray-700">Actividad Reciente</h3>
            </div>

                <div class="space-y-4">
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100">
                        <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Asistencia registrada</p>
                        <p class="text-xs text-gray-500">Sistemas de Información 1 - Grupo SC • hace 5 min</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100">
                        <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Nuevo docente agregado</p>
                        <p class="text-xs text-gray-500">María González • hace 2 horas</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100">
                        <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Horario actualizado</p>
                        <p class="text-xs text-gray-500">Base de Datos - Grupo A • hace 1 día</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Actions -->
        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 shadow-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-lg text-gray-700">Acciones Rápidas</h3>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('docentes.index') }}" class="flex items-center gap-3 p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-100">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-indigo-700">Gestionar Docentes</p>
                        <p class="text-sm text-indigo-600">Agregar, editar o eliminar docentes</p>
                    </div>
                </a>

                <a href="{{ route('administrar.carga') }}" class="flex items-center gap-3 p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-100">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-indigo-700">Crear Grupo</p>
                        <p class="text-sm text-indigo-600">Configurar nuevo grupo y horarios</p>
                    </div>
                </a>

                <a href="{{ route('horarios') }}" class="flex items-center gap-3 p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-100">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-indigo-700">Ver Horarios</p>
                        <p class="text-sm text-indigo-600">Consultar horarios asignados</p>
                    </div>
                </a>

                <a href="{{ route('registro.asistencia') }}" class="flex items-center gap-3 p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-100">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-indigo-700">Registrar Asistencia</p>
                        <p class="text-sm text-indigo-600">Marcar asistencia docente</p>
                    </div>
                </a>
            </div>
        </section>
    </div>
</x-layouts.app>
