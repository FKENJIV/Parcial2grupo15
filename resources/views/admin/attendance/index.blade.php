<x-layouts.app :title="__('Registro de Asistencia')">
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 p-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-indigo-700">Registro de Asistencia Docente</h1>
                    <p class="text-sm text-gray-600 mt-1">Visualiza y gestiona las asistencias de los docentes</p>
                </div>
                <button onclick="openAttendanceModal()" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Registrar Asistencia
                </button>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.attendance.index') }}" class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Docente</label>
                        <select name="teacher_id" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                            <option value="">Todos los docentes</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Grupo</label>
                        <select name="group_id" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                            <option value="">Todos los grupos</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->subjectModel ? $group->subjectModel->name : $group->subject }} - {{ $group->group_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Fecha desde</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Fecha hasta</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Estado</label>
                        <select name="status" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                            <option value="">Todos los estados</option>
                            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Presente</option>
                            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Ausente</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Tardanza</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.attendance.index') }}" class="flex-1 text-center px-4 py-3 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <!-- Attendances Table -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                @if($attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Docente</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Materia</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Grupo</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Horario</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Aula</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Fecha/Hora Registro</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold">Estado</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($attendances as $attendance)
                                    <tr class="hover:bg-indigo-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-indigo-600 font-semibold text-sm">
                                                        {{ substr($attendance->teacher->name, 0, 2) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $attendance->teacher->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $attendance->teacher->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-800">
                                                {{ $attendance->schedule->group->subjectModel ? $attendance->schedule->group->subjectModel->name : $attendance->schedule->group->subject }}
                                            </p>
                                            @if($attendance->schedule->group->subjectModel)
                                                <p class="text-xs text-gray-500">{{ $attendance->schedule->group->subjectModel->code }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-medium rounded-full">
                                                {{ $attendance->schedule->group->group_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $attendance->schedule->day }}</p>
                                            <p class="text-xs text-gray-500">{{ $attendance->schedule->start_time }} - {{ $attendance->schedule->end_time }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-700">{{ $attendance->aula ?? $attendance->schedule->aula }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-800">{{ $attendance->registered_at->format('d/m/Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $attendance->registered_at->format('H:i') }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($attendance->status == 'present')
                                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                                    Presente
                                                </span>
                                            @elseif($attendance->status == 'absent')
                                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                                    Ausente
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                                    Tardanza
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <button onclick="editAttendance({{ $attendance->id }}, '{{ $attendance->status }}', '{{ $attendance->aula }}', '{{ $attendance->registered_at->format('Y-m-d\TH:i') }}')" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Editar">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('admin.attendance.destroy', $attendance) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta asistencia?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $attendances->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">No hay registros de asistencia</h3>
                        <p class="text-gray-500 mb-6">No se encontraron registros con los filtros seleccionados.</p>
                        <button onclick="openAttendanceModal()" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                            Registrar Primera Asistencia
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create/Edit Attendance Modal -->
    <div id="attendanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-4 flex items-center justify-between z-10">
                <h3 class="text-xl font-bold" id="modalTitle">Registrar Nueva Asistencia</h3>
                <button onclick="closeAttendanceModal()" class="text-white hover:bg-indigo-800 rounded-lg p-2 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="attendanceForm" method="POST" action="{{ route('admin.attendance.store') }}" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Docente *</label>
                    <select name="teacher_id" id="teacher_id" class="w-full rounded-lg border border-gray-300 px-4 py-3 bg-white text-gray-800" required onchange="loadTeacherSchedules()">
                        <option value="">Seleccione un docente</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="scheduleContainer" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Horario *</label>
                    <select name="schedule_id" id="schedule_id" class="w-full rounded-lg border border-gray-300 px-4 py-3 bg-white text-gray-800" required>
                        <option value="">Seleccione un horario</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                    <select name="status" id="status" class="w-full rounded-lg border border-gray-300 px-4 py-3 bg-white text-gray-800" required>
                        <option value="present">Presente</option>
                        <option value="absent">Ausente</option>
                        <option value="late">Tardanza</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aula</label>
                    <input type="text" name="aula" id="aula" class="w-full rounded-lg border border-gray-300 px-4 py-3 bg-white text-gray-800" placeholder="Ej: 301-A">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora de Registro *</label>
                    <input type="datetime-local" name="registered_at" id="registered_at" class="w-full rounded-lg border border-gray-300 px-4 py-3 bg-white text-gray-800" required>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Guardar Asistencia
                    </button>
                    <button type="button" onclick="closeAttendanceModal()" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAttendanceModal() {
            document.getElementById('attendanceModal').classList.remove('hidden');
            document.getElementById('attendanceModal').classList.add('flex');
            document.getElementById('modalTitle').textContent = 'Registrar Nueva Asistencia';
            document.getElementById('attendanceForm').action = '{{ route("admin.attendance.store") }}';
            document.getElementById('formMethod').value = 'POST';
            // Reset form and ensure teacher/select are enabled for a new registration
            document.getElementById('attendanceForm').reset();
            document.getElementById('scheduleContainer').style.display = 'none';
            const scheduleSelectInit = document.getElementById('schedule_id');
            if (scheduleSelectInit) {
                scheduleSelectInit.innerHTML = '<option value="">Seleccione un horario</option>';
                scheduleSelectInit.disabled = true; // disabled until a teacher is selected
            }
            const teacherSelectInit = document.querySelector('[name="teacher_id"]');
            if (teacherSelectInit) {
                teacherSelectInit.closest('div').style.display = '';
                teacherSelectInit.disabled = false;
            }
            
            // Set current datetime
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('registered_at').value = now.toISOString().slice(0, 16);
        }

        function closeAttendanceModal() {
            document.getElementById('attendanceModal').classList.add('hidden');
            document.getElementById('attendanceModal').classList.remove('flex');
            // Re-enable fields to avoid "invalid control cannot be focused" on next open
            const scheduleSelect = document.getElementById('schedule_id');
            if (scheduleSelect) scheduleSelect.disabled = false;
            const teacherSelect = document.querySelector('[name="teacher_id"]');
            if (teacherSelect) teacherSelect.disabled = false;
        }

        function editAttendance(id, status, aula, registeredAt) {
            document.getElementById('attendanceModal').classList.remove('hidden');
            document.getElementById('attendanceModal').classList.add('flex');
            document.getElementById('modalTitle').textContent = 'Editar Asistencia';
            document.getElementById('attendanceForm').action = '/admin/attendance/' + id;
            document.getElementById('formMethod').value = 'PUT';
            
            document.getElementById('status').value = status;
            document.getElementById('aula').value = aula;
            document.getElementById('registered_at').value = registeredAt;
            
            // Hide teacher and schedule fields when editing
            const teacherField = document.querySelector('[name="teacher_id"]');
            if (teacherField) {
                teacherField.closest('div').style.display = 'none';
                teacherField.disabled = true;
            }
            const scheduleSelect = document.getElementById('schedule_id');
            if (scheduleSelect) {
                document.getElementById('scheduleContainer').style.display = 'none';
                scheduleSelect.disabled = true;
            }
        }

        async function loadTeacherSchedules() {
            const teacherId = document.getElementById('teacher_id').value;
            const scheduleSelect = document.getElementById('schedule_id');
            const scheduleContainer = document.getElementById('scheduleContainer');
            
            if (!teacherId) {
                scheduleContainer.style.display = 'none';
                if (scheduleSelect) scheduleSelect.disabled = true;
                return;
            }

            try {
                const response = await fetch(`/admin/attendance/teacher-schedules?teacher_id=${teacherId}`);
                const schedules = await response.json();
                
                scheduleSelect.innerHTML = '<option value="">Seleccione un horario</option>';
                
                schedules.forEach(schedule => {
                    const option = document.createElement('option');
                    option.value = schedule.id;
                    option.textContent = schedule.label;
                    scheduleSelect.appendChild(option);
                });
                scheduleSelect.disabled = false;
                scheduleContainer.style.display = 'block';
            } catch (error) {
                console.error('Error loading schedules:', error);
            }
        }

        // Close modal when clicking outside
        document.getElementById('attendanceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAttendanceModal();
            }
        });
    </script>
</x-layouts.app>
