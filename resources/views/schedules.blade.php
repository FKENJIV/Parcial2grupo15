<x-layouts.app :title="__('Ver Horarios')">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold">Mis Horarios Asignados</h2>
        <p class="text-gray-600 mt-1">Consulta los grupos y horarios del periodo actual</p>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('horarios') }}" class="bg-white rounded-2xl shadow-md p-6 mb-6 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Materia</label>
                <select name="subject_id" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                    <option value="">Todas las materias</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Docente</label>
                <select name="teacher_id" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                    <option value="">Todos los docentes</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
            </div>
            <div>
                <a href="{{ route('horarios') }}" class="block w-full text-center px-4 py-3 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors">
                    Limpiar Filtros
                </a>
            </div>
        </div>
    </form>

    <!-- Schedule Cards -->
    @if($groups->isEmpty())
        <div class="bg-white rounded-2xl shadow-md p-12 border border-gray-100 text-center">
            <svg class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">No hay horarios disponibles</h3>
            <p class="text-gray-500">No se encontraron grupos con horarios asignados.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($groups as $group)
                <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-700">{{ $group->subject }} - {{ $group->code }}</h3>
                            <p class="text-xs text-gray-500">Docente: {{ $group->teacher->name ?? 'Sin asignar' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Cupo:</span>
                            <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold">{{ $group->max_students }} estudiantes</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Aula:</span>
                            <span class="text-sm font-semibold text-gray-700">{{ $group->classroom }}</span>
                        </div>

                        @if($group->schedules->isNotEmpty())
                            <div class="border-t border-gray-100 pt-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Horarios de clase:</h4>
                                <div class="space-y-2">
                                    @php
                                        $dayTranslations = [
                                            'monday' => 'Lunes',
                                            'tuesday' => 'Martes',
                                            'wednesday' => 'Miércoles',
                                            'thursday' => 'Jueves',
                                            'friday' => 'Viernes',
                                            'saturday' => 'Sábado',
                                        ];
                                        $sortedSchedules = $group->schedules->sortBy([
                                            ['day', 'asc'],
                                            ['time_block', 'asc']
                                        ]);
                                    @endphp
                                    @foreach($sortedSchedules as $schedule)
                                        <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                                            <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-indigo-700">
                                                    {{ $dayTranslations[$schedule->day] ?? ucfirst($schedule->day) }} 
                                                    {{ sprintf('%02d:00 - %02d:00', $schedule->time_block, $schedule->time_block + 1) }}
                                                </p>
                                                <p class="text-xs text-indigo-600">Aula: {{ $group->classroom }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="border-t border-gray-100 pt-4 text-center text-sm text-gray-500">
                                No hay horarios asignados
                            </div>
                        @endif
                    </div>
                </section>
            @endforeach
        </div>
    @endif
</x-layouts.app>
