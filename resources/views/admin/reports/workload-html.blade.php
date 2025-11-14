<x-layouts.app :title="__('Reporte de Carga Horaria')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">📊 Reporte de Carga Horaria por Docente</h2>
            <p class="text-gray-600 mt-1">Generado el {{ date('d/m/Y H:i') }}</p>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Docente</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Grupos Asignados</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Horas Totales</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($teachers as $teacher)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $teacher['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ $teacher['groups_count'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ $teacher['total_hours'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay datos disponibles</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-100">
                    <tr class="font-bold">
                        <td class="px-6 py-4 text-sm text-gray-900">TOTAL</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $teachers->sum('groups_count') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $teachers->sum('total_hours') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.reports.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Volver a Reportes
            </a>
        </div>
    </div>
</x-layouts.app>
