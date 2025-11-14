<x-layouts.app :title="__('Disponibilidad de Aulas')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Consultar Disponibilidad de Aulas</h2>
            <p class="text-gray-600 mt-1">Verifica qué horarios están ocupados en cada aula</p>
        </div>

        <div class="max-w-7xl mx-auto">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.room-availability.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Aula *</label>
                            <select name="aula" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione un aula</option>
                                @foreach($aulas as $aulaOption)
                                    <option value="{{ $aulaOption }}" {{ $aula == $aulaOption ? 'selected' : '' }}>
                                        {{ $aulaOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Día *</label>
                            <select name="day" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione un día</option>
                                @foreach($days as $dayOption)
                                    <option value="{{ $dayOption }}" {{ $day == $dayOption ? 'selected' : '' }}>
                                        {{ $dayOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Consultar Disponibilidad
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($aula && $day)
                <!-- Resultados -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-4">
                            Disponibilidad del Aula {{ $aula }} - {{ $day }}
                        </h3>

                        @if($availability->isEmpty())
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                <p class="font-semibold">✓ Aula Completamente Disponible</p>
                                <p class="text-sm mt-1">No hay horarios asignados para esta aula en este día.</p>
                            </div>
                        @else
                            <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                                <p class="font-semibold">⚠ Aula Parcialmente Ocupada</p>
                                <p class="text-sm mt-1">{{ $availability->count() }} horario(s) asignado(s)</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horario</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($availability as $schedule)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $schedule->group->subjectModel ? $schedule->group->subjectModel->name : $schedule->group->subject }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $schedule->group->group_name }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $schedule->group->teacher->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Horarios Disponibles -->
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <h4 class="font-semibold text-blue-900 mb-2">💡 Sugerencia de Horarios Disponibles</h4>
                                <p class="text-sm text-blue-700">
                                    Los siguientes rangos horarios están libres en esta aula:
                                </p>
                                <div class="mt-3 space-y-1">
                                    @php
                                        $freeSlots = [];
                                        $dayStart = \Carbon\Carbon::parse('07:00:00');
                                        $dayEnd = \Carbon\Carbon::parse('22:00:00');
                                        
                                        if ($availability->isEmpty()) {
                                            $freeSlots[] = '07:00 - 22:00 (Todo el día)';
                                        } else {
                                            $lastEnd = $dayStart;
                                            foreach ($availability as $schedule) {
                                                $start = \Carbon\Carbon::parse($schedule->start_time);
                                                if ($lastEnd->lt($start)) {
                                                    $freeSlots[] = $lastEnd->format('H:i') . ' - ' . $start->format('H:i');
                                                }
                                                $lastEnd = \Carbon\Carbon::parse($schedule->end_time);
                                            }
                                            if ($lastEnd->lt($dayEnd)) {
                                                $freeSlots[] = $lastEnd->format('H:i') . ' - ' . $dayEnd->format('H:i');
                                            }
                                        }
                                    @endphp
                                    
                                    @forelse($freeSlots as $slot)
                                        <div class="flex items-center text-sm text-blue-800">
                                            <span class="mr-2">✓</span>
                                            <span class="font-medium">{{ $slot }}</span>
                                        </div>
                                    @empty
                                        <p class="text-sm text-blue-700">No hay horarios disponibles en este día.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
