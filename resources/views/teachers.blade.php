<x-layouts.app :title="__('Gestión de Docentes')">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold">Gestión de Docentes</h2>
        <p class="text-gray-600 mt-1">Registro, edición y administración de docentes</p>
    </div>

    <!-- Search and Filters -->
    <form method="GET" action="{{ route('docentes.index') }}" class="bg-white rounded-2xl shadow-md p-6 mb-6 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Buscar docente</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, email o código..." class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Tipo</label>
                <select name="type" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                    <option value="">Todos los tipos</option>
                    <option value="titular" {{ request('type') == 'titular' ? 'selected' : '' }}>Titular</option>
                    <option value="invitado" {{ request('type') == 'invitado' ? 'selected' : '' }}>Invitado</option>
                    <option value="auxiliar" {{ request('type') == 'auxiliar' ? 'selected' : '' }}>Auxiliar</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Estado</label>
                <select name="status" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                    <option value="">Todos los estados</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Especialidad</label>
                <select name="subject_id" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                    <option value="">Todas las materias</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Buscar
                </button>
                <a href="{{ route('docentes.index') }}" class="px-4 py-3 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors">
                    Limpiar
                </a>
            </div>
        </div>
    </form>

    <!-- Teachers List -->
    <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold text-lg text-gray-700">Lista de Docentes</h3>
            <button onclick="openTeacherModal()" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Docente
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 text-left">
                        <th class="pb-3 text-sm font-semibold text-gray-700">Docente</th>
                        <th class="pb-3 text-sm font-semibold text-gray-700">Email</th>
                        <th class="pb-3 text-sm font-semibold text-gray-700">Código</th>
                        <th class="pb-3 text-sm font-semibold text-gray-700">Especialidades</th>
                        <th class="pb-3 text-sm font-semibold text-gray-700">Estado</th>
                        <th class="pb-3 text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($teachers as $teacher)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-semibold">
                                    {{ substr($teacher->name, 0, 1) }}{{ strpos($teacher->name, ' ') !== false ? substr($teacher->name, strpos($teacher->name, ' ') + 1, 1) : '' }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $teacher->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $teacher->type ?? 'Docente titular' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-600">{{ $teacher->email }}</td>
                        <td class="py-4 text-sm text-gray-600">{{ $teacher->code ?? 'DOC-' . str_pad($teacher->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-4">
                            @if($teacher->subjects->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($teacher->subjects->take(3) as $subject)
                                        <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">
                                            {{ $subject->code }}
                                        </span>
                                    @endforeach
                                    @if($teacher->subjects->count() > 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                            +{{ $teacher->subjects->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Sin especialidades</span>
                            @endif
                        </td>
                        <td class="py-4">
                            @if($teacher->status ?? 'active' === 'active')
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">Activo</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Inactivo</span>
                            @endif
                        </td>
                        <td class="py-4">
                            <div class="flex gap-2">
                                <button class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Editar" onclick="openTeacherModal(true, '{{ $teacher->id }}')">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Dar de baja" onclick="confirmDelete({{ $teacher->id }}, '{{ $teacher->name }}')">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($teachers->hasPages())
        <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-4">
            <p class="text-sm text-gray-600">
                Mostrando {{ $teachers->firstItem() ?? 0 }} a {{ $teachers->lastItem() ?? 0 }} de {{ $teachers->total() }} docentes
            </p>
            <div class="flex gap-2">
                {{-- Previous Page Link --}}
                @if ($teachers->onFirstPage())
                    <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm cursor-not-allowed">Anterior</span>
                @else
                    <a href="{{ $teachers->previousPageUrl() }}" class="px-3 py-2 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-200 transition-colors">Anterior</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($teachers->getUrlRange(1, $teachers->lastPage()) as $page => $url)
                    @if ($page == $teachers->currentPage())
                        <span class="px-3 py-2 bg-indigo-500 text-white rounded-lg text-sm font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-200 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($teachers->hasMorePages())
                    <a href="{{ $teachers->nextPageUrl() }}" class="px-3 py-2 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-200 transition-colors">Siguiente</a>
                @else
                    <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm cursor-not-allowed">Siguiente</span>
                @endif
            </div>
        </div>
        @endif
    </section>

    <!-- Add/Edit Teacher Modal -->
    <x-teacher-modal :subjects="$subjects" />

    <script>
        // The teacher modal and its JS (open/close/fill/validation) are provided by the
        // `x-teacher-modal` component. Here we only keep helper functions not provided
        // by the component (delete confirmation).

        function confirmDelete(teacherId, teacherName) {
            if (confirm(`¿Estás seguro de que deseas dar de baja al docente "${teacherName}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/docentes/${teacherId}`;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                form.appendChild(methodInput);
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-layouts.app>
