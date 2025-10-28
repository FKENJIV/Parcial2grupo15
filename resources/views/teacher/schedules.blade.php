<x-layouts.app :title="__('Mis Horarios Asignados')">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Mis Horarios</h2>
                <p class="text-gray-600 mt-1">CU3: Consulta de grupos y horarios asignados</p>
            </div>
            <a href="{{ route('teacher.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al Panel
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-md p-6 border border-indigo-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Grupos</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $stats['total_groups'] ?? 0 }}</p>
                </div>
                <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6 border border-indigo-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Horas Semanales</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $stats['total_hours'] ?? 0 }}</p>
                </div>
                <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6 border border-indigo-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Estudiantes</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $stats['total_students'] ?? 0 }}</p>
                </div>
                <div class="h-12 w-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Grid -->
    <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Horario Semanal</h3>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimir
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b-2 border-indigo-200">
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Hora</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Lunes</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Martes</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Miércoles</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Jueves</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Viernes</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Sábado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach(range(7, 20) as $hour)
                    @php
                        $timeSlot = sprintf('%02d:00-%02d:00', $hour, $hour + 1);
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm text-gray-600 font-medium">{{ $timeSlot }}</td>
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                        <td class="py-3 px-4 text-sm">
                            @if(isset($scheduleGrid[$day][$hour]))
                            <div class="bg-indigo-100 border-l-4 border-indigo-600 rounded px-3 py-2">
                                <p class="font-semibold text-indigo-900 text-xs">{{ $scheduleGrid[$day][$hour]['subject'] }}</p>
                                <p class="text-indigo-700 text-xs">{{ $scheduleGrid[$day][$hour]['code'] }}</p>
                                <p class="text-indigo-600 text-xs mt-1">📍 {{ $scheduleGrid[$day][$hour]['classroom'] }}</p>
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <!-- My Groups List -->
    <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Mis Grupos Asignados</h3>
        
        @if($groups->isEmpty())
        <div class="text-center py-12 text-gray-500">
            <svg class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="text-lg mb-2">No tienes grupos asignados todavía</p>
            <p class="text-sm mb-4">Crea tu primer grupo para empezar a asignar horarios</p>
            <a href="{{ route('teacher.groups.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Crear Grupo
            </a>
        </div>
        @else
        <div class="grid gap-4">
            @foreach($groups as $group)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $group->subject }}</h4>
                        <p class="text-sm text-gray-600 mt-1">Código: {{ $group->code }} | Aula: {{ $group->classroom }}</p>
                        <p class="text-sm text-gray-500 mt-1">Cupo: {{ $group->max_students }} estudiantes</p>
                        <div class="flex gap-2 mt-2">
                            @foreach($group->schedules as $schedule)
                            @php
                                $dayNames = ['monday' => 'Lun', 'tuesday' => 'Mar', 'wednesday' => 'Mié', 'thursday' => 'Jue', 'friday' => 'Vie', 'saturday' => 'Sáb'];
                                $dayName = $dayNames[$schedule->day] ?? $schedule->day;
                                $timeSlot = sprintf('%02d:00', $schedule->time_block);
                            @endphp
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded">
                                {{ $dayName }} {{ $timeSlot }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>
</x-layouts.app>
