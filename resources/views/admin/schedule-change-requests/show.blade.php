<x-layouts.app :title="__('Detalle de Solicitud')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ __('Detalle de Solicitud de Cambio') }}</h2>
            </div>
            <a href="{{ route('admin.schedule-change-requests.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Información del Docente -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Información del Docente</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nombre</p>
                            <p class="font-medium">{{ $scheduleChangeRequest->teacher->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $scheduleChangeRequest->teacher->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

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
                    <h3 class="text-lg font-semibold mb-2">Razón de la Solicitud</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $scheduleChangeRequest->reason }}</p>
                </div>
            </div>

            <!-- Estado y Acciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">Estado de la Solicitud</h3>
                            <span class="mt-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($scheduleChangeRequest->status == 'pendiente') bg-yellow-100 text-yellow-800
                                @elseif($scheduleChangeRequest->status == 'aprobado') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($scheduleChangeRequest->status) }}
                            </span>
                        </div>
                    </div>

                    @if($scheduleChangeRequest->status == 'pendiente')
                        <div class="mt-6 space-y-4">
                            <!-- Formulario de Aprobación -->
                            <form method="POST" action="{{ route('admin.schedule-change-requests.approve', $scheduleChangeRequest) }}" class="border-2 border-green-300 rounded-lg p-4 bg-green-50">
                                @csrf
                                <h4 class="font-semibold text-green-700 mb-3">Aprobar Solicitud</h4>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Comentarios (opcional)</label>
                                    <textarea name="admin_comments" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="Agregue comentarios sobre la aprobación..."></textarea>
                                </div>
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    ✓ Aprobar y Aplicar Cambio
                                </button>
                            </form>

                            <!-- Formulario de Rechazo -->
                            <form method="POST" action="{{ route('admin.schedule-change-requests.reject', $scheduleChangeRequest) }}" class="border-2 border-red-300 rounded-lg p-4 bg-red-50">
                                @csrf
                                <h4 class="font-semibold text-red-700 mb-3">Rechazar Solicitud</h4>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Razón del rechazo *</label>
                                    <textarea name="admin_comments" rows="2" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Explique por qué se rechaza la solicitud..."></textarea>
                                </div>
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    ✗ Rechazar Solicitud
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Información de Revisión -->
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
        </div>
    </div>
    </div>
</x-layouts.app>
