<x-layouts.app :title="__('Reporte de Asistencias')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">📊 Reporte de Asistencias</h2>
            <p class="text-gray-600 mt-1">Período: {{ date('d/m/Y', strtotime($date_from)) }} - {{ date('d/m/Y', strtotime($date_to)) }}</p>
            @if($teacher)
            <p class="text-gray-600">Docente: {{ $teacher->name }}</p>
            @endif
            @if($group)
            <p class="text-gray-600">Grupo: {{ $group->name }}</p>
            @endif
            <p class="text-gray-600">Generado el {{ date('d/m/Y H:i') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-green-100 border-l-4 border-green-500 p-4">
                <p class="font-bold text-green-700">Presentes: {{ $attendances->where('status', 'present')->count() }}</p>
            </div>
            <div class="bg-red-100 border-l-4 border-red-500 p-4">
                <p class="font-bold text-red-700">Ausentes: {{ $attendances->where('status', 'absent')->count() }}</p>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Docente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Grupo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Materia</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendances as $attendance)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('d/m/Y H:i', strtotime($attendance->registered_at)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->teacher->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->schedule->group->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->schedule->group->subjectModel->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($attendance->status === 'present')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Presente</span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ausente</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay registros de asistencia en este período</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.reports.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Volver a Reportes
            </a>
        </div>
    </div>
</x-layouts.app>
