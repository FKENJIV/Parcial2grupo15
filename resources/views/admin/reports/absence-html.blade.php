<x-layouts.app :title="__('Reporte de Ausencias')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">❌ Reporte de Ausencias</h2>
            <p class="text-gray-600 mt-1">Período: {{ date('d/m/Y', strtotime($date_from)) }} - {{ date('d/m/Y', strtotime($date_to)) }}</p>
            <p class="text-gray-600">Generado el {{ date('d/m/Y H:i') }}</p>
        </div>

        <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-6">
            <p class="font-bold text-red-700">Total de Ausencias: {{ $total_absences }}</p>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-red-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Docente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Grupo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Materia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($absences as $absence)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('d/m/Y', strtotime($absence->registered_at)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $absence->teacher->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $absence->schedule->group->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $absence->schedule->group->subjectModel->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $absence->observations ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay ausencias registradas en este período</td>
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
