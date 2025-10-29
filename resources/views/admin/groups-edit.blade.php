<x-layouts.app :title="__('Editar Grupo')">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Editar Grupo</h2>
                <p class="text-gray-600 mt-1">Modificar información del grupo y sus horarios</p>
            </div>
            <a href="{{ route('admin.groups.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Volver a la lista
            </a>
        </div>
    </div>

    <form action="{{ route('admin.groups.update', $group) }}" method="POST" class="space-y-6" id="groupEditForm">
        @csrf
        @method('PUT')

        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Grupo</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Docente *</label>
                    <select name="teacher_id" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                        <option value="">Seleccionar docente...</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ $group->teacher && $group->teacher->id == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Materia *</label>
                    <select name="subject_id" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                        <option value="">Seleccionar materia...</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ $group->subject_id == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Nombre del Grupo *</label>
                    <input type="text" name="group_name" required value="{{ old('group_name', $group->name) }}" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Descripción</label>
                    <textarea name="description" rows="2" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">{{ old('description', $group->description ?? '') }}</textarea>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Horarios del Grupo</h3>
                <button type="button" id="addScheduleBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Agregar Horario</button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full" id="schedulesTable">
                    <thead>
                        <tr class="border-b-2 border-indigo-200">
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Día</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Hora Inicio</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Hora Fin</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Aula</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group->schedules as $i => $schedule)
                            <tr data-index="{{ $i }}">
                                <td class="py-2 px-4">
                                    <select name="schedules[{{ $i }}][day]" required class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-gray-800">
                                        @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'] as $d)
                                            <option value="{{ $d }}" {{ $schedule->day_of_week == $d ? 'selected' : '' }}>{{ $d }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="schedules[{{ $i }}][id]" value="{{ $schedule->id }}">
                                </td>
                                <td class="py-2 px-4"><input type="time" name="schedules[{{ $i }}][start_time]" value="{{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }}" required class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-gray-800" /></td>
                                <td class="py-2 px-4"><input type="time" name="schedules[{{ $i }}][end_time]" value="{{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }}" required class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-gray-800" /></td>
                                <td class="py-2 px-4"><input type="text" name="schedules[{{ $i }}][aula]" value="{{ $schedule->aula ?? '' }}" required class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-gray-800" /></td>
                                <td class="py-2 px-4"><button type="button" class="removeScheduleBtn px-3 py-1 bg-red-100 text-red-700 rounded">Eliminar</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">Guardar Cambios</button>
            <a href="{{ route('admin.groups.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg">Cancelar</a>
        </div>
    </form>
</x-layouts.app>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('schedulesTable').getElementsByTagName('tbody')[0];
    const addBtn = document.getElementById('addScheduleBtn');

    function updateIndices() {
        Array.from(table.querySelectorAll('tr')).forEach((tr, idx) => {
            tr.dataset.index = idx;
            tr.querySelectorAll('select, input').forEach(input => {
                const name = input.getAttribute('name') || '';
                const newName = name.replace(/schedules\[\d+\]/, `schedules[${idx}]`);
                input.setAttribute('name', newName);
            });
        });
    }

    addBtn.addEventListener('click', function() {
        const idx = table.querySelectorAll('tr').length;
        const tr = document.createElement('tr');
        tr.dataset.index = idx;
        tr.innerHTML = `
            <td class="py-2 px-4">
                <select name="schedules[${idx}][day]" required class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-gray-800">
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                </select>
            </td>
            <td class="py-2 px-4"><input type="time" name="schedules[${idx}][start_time]" required class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-gray-800" /></td>
            <td class="py-2 px-4"><input type="time" name="schedules[${idx}][end_time]" required class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-gray-800" /></td>
            <td class="py-2 px-4"><input type="text" name="schedules[${idx}][aula]" required class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-gray-800" /></td>
            <td class="py-2 px-4"><button type="button" class="removeScheduleBtn px-3 py-1 bg-red-100 text-red-700 rounded">Eliminar</button></td>
        `;
        table.appendChild(tr);
        updateIndices();
    });

    table.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('removeScheduleBtn')) {
            const tr = e.target.closest('tr');
            tr.remove();
            updateIndices();
        }
    });
});
</script>
