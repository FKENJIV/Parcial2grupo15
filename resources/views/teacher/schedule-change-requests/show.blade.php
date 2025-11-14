<x-layouts.app :title="__('Detalle de Mi Solicitud')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ __('Detalle de Mi Solicitud') }}</h2>
            </div>
            <a href="{{ route('teacher.schedule-change-requests.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Comparación de Horarios -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Comparación de Horarios</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Horario Actual -->
                        <div class="border-2 border-gray-300 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 mb-3">Horario Actual</h4>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm text-gray-500">Materia</p>
                                    <p class="font-medium">{{ $scheduleChangeRequest->schedule->group->subjectModel ? $scheduleChangeRequest->schedule->group->subjectModel->name : $scheduleChangeRequest->schedule->group->subject }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Grupo</p>
                                    <p class="font-medium">{{ $scheduleChangeRequest->schedule->group->group_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Día</p>
                                    <p class="font-medium">{{ $scheduleChangeRequest->schedule->day_of_week }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Horario</p>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($scheduleChangeRequest->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($scheduleChangeRequest->schedule->end_time)->format('H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Aula</p>
                                    <p class="font-medium">{{ $scheduleChangeRequest->schedule->aula }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Horario Propuesto -->
                        <div class="border-2 border-blue-500 rounded-lg p-4 bg-blue-50">
                            <h4 class="font-semibold text-blue-700 mb-3">Horario Propuesto</h4>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm text-gray-500">Materia</p>
                                    <p class="font-medium">{{ $scheduleChangeRequest->schedule->group->subjectModel ? $scheduleChangeRequest->schedule->group->subjectModel->name : $scheduleChangeRequest->schedule->group->subject }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Grupo</p>
                                    <p class="font-medium">{{ $scheduleChangeRequest->schedule->group->group_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Día</p>
                                    <p class="font-medium text-blue-700">{{ $scheduleChangeRequest->new_day_of_week }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Horario</p>
                                    <p class="font-medium text-blue-700">{{ \Carbon\Carbon::parse($scheduleChangeRequest->new_start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($scheduleChangeRequest->new_end_time)->format('H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Aula</p>
                                    <p class="font-medium text-blue-700">{{ $scheduleChangeRequest->new_aula ?? $scheduleChangeRequest->schedule->aula }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Razón de la Solicitud -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-2">Mi Razón</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $scheduleChangeRequest->reason }}</p>
                </div>
            </div>

            <!-- Estado -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-2">Estado de la Solicitud</h3>
                    <div class="flex items-center space-x-3">
                        <span class="px-4 py-2 inline-flex text-base leading-5 font-semibold rounded-full 
                            @if($scheduleChangeRequest->status == 'pendiente') bg-yellow-100 text-yellow-800
                            @elseif($scheduleChangeRequest->status == 'aprobado') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($scheduleChangeRequest->status) }}
                        </span>
                        
                        @if($scheduleChangeRequest->status == 'pendiente')
                            <p class="text-sm text-gray-600">Tu solicitud está siendo revisada por el administrador.</p>
                        @elseif($scheduleChangeRequest->status == 'aprobado')
                            <p class="text-sm text-green-600">¡Tu solicitud fue aprobada! El horario ha sido actualizado.</p>
                        @else
                            <p class="text-sm text-red-600">Tu solicitud fue rechazada.</p>
                        @endif
                    </div>

                    @if($scheduleChangeRequest->status != 'pendiente')
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Revisado por</p>
                                    <p class="font-medium">{{ $scheduleChangeRequest->reviewer ? $scheduleChangeRequest->reviewer->name : 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Fecha de revisión</p>
                                    <p class="font-medium">{{ $scheduleChangeRequest->reviewed_at ? $scheduleChangeRequest->reviewed_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                            @if($scheduleChangeRequest->admin_comments)
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500">Comentarios del administrador</p>
                                    <p class="font-medium whitespace-pre-line">{{ $scheduleChangeRequest->admin_comments }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Solicitud creada el</p>
                            <p class="font-medium">{{ $scheduleChangeRequest->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Última actualización</p>
                            <p class="font-medium">{{ $scheduleChangeRequest->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-layouts.app>
