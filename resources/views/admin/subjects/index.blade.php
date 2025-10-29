<x-layouts.app :title="__('Administrar Materias')">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Administrar Materias</h2>
                <p class="text-gray-600 mt-1">Gestiona el catálogo de materias disponibles</p>
            </div>
            <button onclick="openSubjectModal()" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Materia
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
            <div class="flex">
                <svg class="h-5 w-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex">
                <svg class="h-5 w-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Subjects Table -->
    <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Lista de Materias</h3>
        
        @if($subjects->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <svg class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-lg mb-2">No hay materias registradas</p>
                <p class="text-sm">Comienza agregando una nueva materia</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b-2 border-indigo-200">
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Código</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Nombre</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Descripción</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Créditos</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Grupos</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Estado</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($subjects as $subject)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm font-medium text-gray-800">{{ $subject->code }}</td>
                                <td class="py-3 px-4 text-sm text-gray-800">{{ $subject->name }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ Str::limit($subject->description, 50) ??  '-' }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ $subject->credits }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ $subject->groups->count() }}</td>
                                <td class="py-3 px-4 text-sm">
                                    @if($subject->active)
                                        <span id="subject-status-{{ $subject->id }}" class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Activa</span>
                                    @else
                                        <span id="subject-status-{{ $subject->id }}" class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">Inactiva</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <div class="flex gap-2">
                                        <button onclick="editSubject({{ json_encode($subject) }})" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition-colors">
                                            Editar
                                        </button>
                                        <button onclick="toggleSubjectActive({{ $subject->id }}, this)" class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 transition-colors" title="Activar/Desactivar rápidamente">
                                            Alternar
                                        </button>
                                        <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta materia?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-layouts.app>

<!-- Subject Modal -->
<div id="subjectModal" class="fixed inset-0 bg-indigo-900 bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="relative w-full max-w-[90vw] sm:max-w-2xl mx-2 sm:mx-4">
        <div class="relative bg-white shadow-[0_30px_80px_rgba(2,6,23,0.12)] border-2 border-indigo-200
            rounded-none sm:rounded-xl max-h-[90vh] overflow-auto">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-indigo-100 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-white shadow-md">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 id="modalTitle" class="text-lg font-semibold text-indigo-700">Nueva Materia</h3>
                    </div>
                    <button onclick="closeSubjectModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form id="subjectForm" class="p-4 sm:p-6 space-y-6" method="POST" action="{{ route('admin.subjects.store') }}">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Nombre de la Materia *</label>
                        <input type="text" name="name" required placeholder="Ej: Sistemas de Información 1" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Código *</label>
                        <input type="text" name="code" required placeholder="Ej: INF-111" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Descripción</label>
                    <textarea name="description" rows="3" placeholder="Descripción breve de la materia" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Créditos *</label>
                        <input type="number" name="credits" required min="1" max="12" value="4" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="active" value="1" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700">Materia Activa</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="flex-1 px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                        <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span id="submitButtonText">Crear Materia</span>
                    </button>
                    <button type="button" onclick="closeSubjectModal()" class="px-4 py-3 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openSubjectModal() {
    const form = document.getElementById('subjectForm');
    const modalTitle = document.getElementById('modalTitle');
    const submitButtonText = document.getElementById('submitButtonText');
    const formMethod = document.getElementById('formMethod');
    
    form.reset();
    form.action = "{{ route('admin.subjects.store') }}";
    formMethod.value = 'POST';
    modalTitle.textContent = 'Nueva Materia';
    submitButtonText.textContent = 'Crear Materia';
    document.querySelector('[name="active"]').checked = true;
    
    document.getElementById('subjectModal').classList.remove('hidden');
}

function editSubject(subject) {
    const form = document.getElementById('subjectForm');
    const modalTitle = document.getElementById('modalTitle');
    const submitButtonText = document.getElementById('submitButtonText');
    const formMethod = document.getElementById('formMethod');
    
    form.action = `/admin/subjects/${subject.id}`;
    formMethod.value = 'PUT';
    modalTitle.textContent = 'Editar Materia';
    submitButtonText.textContent = 'Actualizar Materia';
    
    form.querySelector('[name="name"]').value = subject.name;
    form.querySelector('[name="code"]').value = subject.code;
    form.querySelector('[name="description"]').value = subject.description || '';
    form.querySelector('[name="credits"]').value = subject.credits;
    // Normalize the active value because JSON sometimes serializes booleans as
    // 1/0 or '1'/'0'. Treat only true, 1 or '1' as active.
    const activeValue = subject.active === true || subject.active === 1 || subject.active === '1';
    form.querySelector('[name="active"]').checked = activeValue;
    
    document.getElementById('subjectModal').classList.remove('hidden');
}

function closeSubjectModal() {
    document.getElementById('subjectModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('subjectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSubjectModal();
    }
});
</script>

<script>
    async function toggleSubjectActive(subjectId, btn) {
        try {
            btn.disabled = true;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch(`{{ url('admin/subjects') }}/${subjectId}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({})
            });

            const json = await res.json();
            if (!res.ok || !json.success) {
                (window.showToast || ((m)=>alert(m)))(json.message || 'Error al cambiar el estado', 'error');
                return;
            }

            // Update badge
            const badge = document.getElementById(`subject-status-${subjectId}`);
            if (badge) {
                if (json.active) {
                    badge.textContent = 'Activa';
                    badge.className = 'px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full';
                } else {
                    badge.textContent = 'Inactiva';
                    badge.className = 'px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full';
                }
            }

            (window.showToast || ((m)=>alert(m)))(json.message || 'Estado actualizado', 'success');
        } catch (e) {
            (window.showToast || ((m)=>alert(m)))('Error de red', 'error');
        } finally {
            btn.disabled = false;
        }
    }
</script>
