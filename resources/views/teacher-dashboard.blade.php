<x-layouts.app :title="__('Panel del Docente')">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Bienvenido, {{ auth()->user()->name }}</h2>
        <p class="text-gray-600 mt-1">Panel de control del docente</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Groups -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-indigo-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Grupos Asignados</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalGroups }}</p>
                </div>
                <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Classes Today -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-indigo-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Clases Hoy</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $todayClasses->count() }}</p>
                </div>
                <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-indigo-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Asistencia</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ number_format($attendanceRate, 1) }}%</p>
                </div>
                <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <section class="bg-white rounded-2xl shadow-md p-6 mb-8 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- CU3: Ver horarios -->
            <a href="{{ route('teacher.schedules') }}" class="group p-6 border-2 border-indigo-200 rounded-xl hover:border-indigo-400 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                        <svg class="h-6 w-6 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Ver Horarios</h4>
                        <p class="text-sm text-gray-600">Consultar grupos y horarios asignados</p>
                    </div>
                </div>
            </a>

            <!-- CU4: Crear grupo -->
            <a href="{{ route('teacher.groups.create') }}" class="group p-6 border-2 border-indigo-200 rounded-xl hover:border-indigo-400 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                        <svg class="h-6 w-6 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Crear Grupo</h4>
                        <p class="text-sm text-gray-600">Asignar grupo y horario</p>
                    </div>
                </div>
            </a>

            <!-- CU5: Registrar asistencia -->
            <a href="{{ route('teacher.attendance') }}" class="group p-6 border-2 border-indigo-200 rounded-xl hover:border-indigo-400 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                        <svg class="h-6 w-6 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Registrar Asistencia</h4>
                        <p class="text-sm text-gray-600">Marcar asistencia a clase</p>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <!-- Schedule Today -->
    <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Horario de Hoy</h3>
        
        @if($todayClasses->count() > 0)
            <div class="space-y-3">
                @foreach($todayClasses as $schedule)
                    <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-indigo-600 text-white font-bold">
                                {{ $schedule->time_block }}:00
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $schedule->group->subject }}</h4>
                                <p class="text-sm text-gray-600">{{ $schedule->group->code }} • Aula: {{ $schedule->group->classroom }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                {{ ucfirst($schedule->day) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p>No tienes clases programadas para hoy</p>
                <p class="text-sm mt-2">Crea un grupo para empezar a asignar horarios</p>
            </div>
        @endif
    </section>
</x-layouts.app>
