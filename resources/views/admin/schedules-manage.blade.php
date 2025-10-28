<x-layouts.app :title="__('Gestión de Horarios')">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Gestión de Horarios</h2>
        <p class="text-gray-600 mt-1">Administrar horarios de todos los docentes</p>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
        <div class="flex">
            <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Teachers List -->
    @foreach($teachers as $teacher)
    <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-semibold">
                    {{ substr($teacher->name, 0, 1) }}{{ strpos($teacher->name, ' ') !== false ? substr($teacher->name, strpos($teacher->name, ' ') + 1, 1) : '' }}
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $teacher->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $teacher->email }} | Código: {{ $teacher->code ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                <span class="font-semibold">{{ $teacher->groups->count() }}</span> grupo(s)
            </div>
        </div>

        @if($teacher->groups->isEmpty())
        <div class="text-center py-8 text-gray-500">
            <p class="text-sm">Este docente no tiene grupos asignados</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($teacher->groups as $group)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <h4 class="font-semibold text-gray-800">{{ $group->subject }}</h4>
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded">{{ $group->code }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            Aula: {{ $group->classroom }} | Cupo: {{ $group->max_students }} estudiantes
                        </p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            @php
                                $dayNames = ['monday' => 'Lun', 'tuesday' => 'Mar', 'wednesday' => 'Mié', 'thursday' => 'Jue', 'friday' => 'Vie', 'saturday' => 'Sáb'];
                            @endphp
                            @foreach($group->schedules as $schedule)
                            @php
                                $dayName = $dayNames[$schedule->day] ?? $schedule->day;
                                $timeSlot = sprintf('%02d:00-%02d:00', $schedule->time_block, $schedule->time_block + 1);
                            @endphp
                            <span class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-full">
                                {{ $dayName }} {{ $timeSlot }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <a href="{{ route('admin.schedules.edit', $group->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Editar">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form action="{{ route('admin.schedules.destroy', $group->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este grupo y sus horarios?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>
    @endforeach

    @if($teachers->isEmpty())
    <div class="bg-white rounded-2xl shadow-md p-12 text-center text-gray-500">
        <svg class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <p>No hay docentes registrados en el sistema</p>
    </div>
    @endif
</x-layouts.app>
