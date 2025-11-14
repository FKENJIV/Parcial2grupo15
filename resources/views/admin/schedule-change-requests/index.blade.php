<x-layouts.app :title="__('Solicitudes de Cambio')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">{{ __('Solicitudes de Cambio de Horario') }}</h2>
            <p class="text-gray-600 mt-1">Gestiona las solicitudes de cambio de horario de los docentes</p>
        </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.schedule-change-requests.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="aprobado" {{ request('status') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                <option value="rechazado" {{ request('status') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Docente</label>
                            <select name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                @foreach(\App\Models\User::whereIn('role', ['teacher', 'docente'])->orderBy('name')->get() as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Solicitudes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $request->teacher->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $request->schedule->group->subjectModel ? $request->schedule->group->subjectModel->name : $request->schedule->group->subject }}<br>
                                            <span class="text-xs text-gray-400">{{ $request->schedule->group->group_name }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $request->schedule->day_of_week }}<br>
                                            {{ \Carbon\Carbon::parse($request->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($request->schedule->end_time)->format('H:i') }}<br>
                                            <span class="text-xs text-gray-400">{{ $request->schedule->aula }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $request->new_day_of_week }}<br>
                                            {{ \Carbon\Carbon::parse($request->new_start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($request->new_end_time)->format('H:i') }}<br>
                                            <span class="text-xs text-gray-400">{{ $request->new_aula ?? $request->schedule->aula }}</span>
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
                                            <a href="{{ route('admin.schedule-change-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No hay solicitudes de cambio de horario.
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
