<x-layouts.app :title="__('Reporte de Docentes')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">👨‍🏫 Reporte de Docentes</h2>
            <p class="text-gray-600 mt-1">Total de docentes: {{ $teachers->count() }}</p>
            <p class="text-gray-600">Generado el {{ date('d/m/Y H:i') }}</p>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Materias</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($teachers as $teacher)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $teacher->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $teacher->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst($teacher->type ?? 'N/A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($teacher->status === 'active')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($teacher->subjects && $teacher->subjects->count() > 0)
                                {{ $teacher->subjects->pluck('name')->join(', ') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay docentes registrados</td>
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
