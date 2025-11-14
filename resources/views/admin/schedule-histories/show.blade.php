<x-layouts.app :title="__('Detalle del Cambio')">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del Cambio de Horario') }}
            </h2>
            <a href="{{ route('admin.schedule-histories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Información General -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Información del Cambio</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Fecha del Cambio</h4>
                            <p class="mt-1 text-lg text-gray-900">{{ $scheduleHistory->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Realizado por</h4>
                            <p class="mt-1 text-lg text-gray-900">{{ $scheduleHistory->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $scheduleHistory->user->email }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Tipo de Cambio</h4>
                            <p class="mt-1">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    @if($scheduleHistory->change_type == 'created') bg-green-100 text-green-800
                                    @elseif($scheduleHistory->change_type == 'updated') bg-gray-100 text-black
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($scheduleHistory->change_type) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Materia/Grupo</h4>
                            <p class="mt-1 text-lg text-gray-900">
                                {{ $scheduleHistory->schedule->group->subjectModel ? $scheduleHistory->schedule->group->subjectModel->name : $scheduleHistory->schedule->group->subject }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $scheduleHistory->schedule->group->group_name }}</p>
                        </div>
                    </div>

                    @if($scheduleHistory->reason)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-500">Razón del Cambio</h4>
                            <p class="mt-1 text-gray-900 whitespace-pre-line">{{ $scheduleHistory->reason }}</p>
                        </div>
                    @endif

                    @if($scheduleHistory->changeRequest)
                        <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                            <h4 class="text-sm font-medium text-black">Solicitud de Cambio Asociada</h4>
                            <p class="mt-1 text-sm text-gray-700">
                                Este cambio fue realizado en respuesta a una solicitud del docente 
                                <strong>{{ $scheduleHistory->changeRequest->teacher->name }}</strong>
                            </p>
                            <a href="{{ route('admin.schedule-change-requests.show', $scheduleHistory->changeRequest) }}" 
                               class="mt-2 inline-block text-black hover:text-gray-700 text-sm underline">
                                Ver solicitud completa →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Comparación de Valores -->
            @if($scheduleHistory->old_values || $scheduleHistory->new_values)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Comparación de Valores</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Valores Anteriores -->
                            @if($scheduleHistory->old_values)
                                <div class="border-2 border-gray-300 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700 mb-3">Valores Anteriores</h4>
                                    <div class="space-y-2">
                                        @foreach($scheduleHistory->old_values as $key => $value)
                                            <div>
                                                <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</p>
                                                <p class="font-medium">{{ is_array($value) ? json_encode($value) : $value }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Valores Nuevos -->
                            @if($scheduleHistory->new_values)
                                <div class="border-2 border-gray-400 rounded-lg p-4 bg-gray-50">
                                    <h4 class="font-semibold text-black mb-3">Valores Nuevos</h4>
                                    <div class="space-y-2">
                                        @foreach($scheduleHistory->new_values as $key => $value)
                                            <div>
                                                <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</p>
                                                <p class="font-medium text-black">{{ is_array($value) ? json_encode($value) : $value }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Información del Horario Actual -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Horario Actual</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Día</p>
                            <p class="font-medium">{{ $scheduleHistory->schedule->day_of_week }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Hora Inicio</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($scheduleHistory->schedule->start_time)->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Hora Fin</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($scheduleHistory->schedule->end_time)->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Aula</p>
                            <p class="font-medium">{{ $scheduleHistory->schedule->aula }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
