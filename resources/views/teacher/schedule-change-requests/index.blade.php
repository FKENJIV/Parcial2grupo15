<x-layouts.app :title="__('Mis Solicitudes de Cambio de Horario')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ __('Mis Solicitudes de Cambio de Horario') }}</h2>
            </div>
            <a href="{{ route('teacher.schedule-change-requests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nueva Solicitud
            </a>
        </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia/Grupo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario Actual</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario Propuesto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($requests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $request->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $request->schedule->group->subjectModel ? $request->schedule->group->subjectModel->name : $request->schedule->group->subject }}<br>
                                            <span class="text-xs text-gray-400">{{ $request->schedule->group->group_name }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $request->schedule->day_of_week }}<br>
                                            {{ \Carbon\Carbon::parse($request->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($request->schedule->end_time)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $request->new_day_of_week }}<br>
                                            {{ \Carbon\Carbon::parse($request->new_start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($request->new_end_time)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($request->status == 'pendiente') bg-yellow-100 text-yellow-800
                                                @elseif($request->status == 'aprobado') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('teacher.schedule-change-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900">Ver Detalle</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No has realizado solicitudes de cambio de horario.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-layouts.app>
