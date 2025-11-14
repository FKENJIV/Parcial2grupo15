<x-layouts.app :title="__('Crear Grupo')">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Crear Grupo (Administrador)</h2>
                <p class="text-gray-600 mt-1">Asignar grupo a un docente y definir horarios</p>
            </div>
            <a href="{{ route('admin.groups.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a Grupos
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.groups.store') }}" method="POST" class="space-y-6" id="groupForm">
        @csrf

        <!-- Group Information -->
        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Grupo</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Asignar a Docente *</label>
                    <select name="teacher_id" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccionar docente...</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }} - {{ $teacher->email }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Docente responsable del grupo</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Materia / Asignatura *</label>
                    <select name="subject_id" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccionar materia...</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Nombre del Grupo *</label>
                    <input type="text" name="group_name" required placeholder="Ej: Grupo A, Grupo SC, Paralelo 1" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('group_name') }}" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Capacidad (Cupo) *</label>
                    <input type="number" name="capacity" required min="40" placeholder="Mínimo 40 estudiantes" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('capacity', 40) }}" />
                    <p class="text-xs text-gray-500 mt-1">Cupo mínimo: 40 estudiantes</p>
                </div>
            </div>
        </section>

        <!-- Schedule Selection -->
        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Horarios del Grupo</h3>
                    <p class="text-sm text-gray-600 mt-1">Define los horarios en los que se impartirá el grupo</p>
                </div>
                <button type="button" onclick="addScheduleRow()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Agregar Horario
                </button>
            </div>

            <div id="schedulesContainer" class="space-y-3">
                <!-- Schedule rows will be added here -->
            </div>

            <p class="text-xs text-gray-500 mt-4">* Agrega al menos un horario para el grupo</p>
        </section>

        <!-- Actions -->
        <div class="flex gap-3">
            <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Crear Grupo
            </button>
            <a href="{{ route('admin.groups.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                Cancelar
            </a>
        </div>
    </form>

    <script>
        let scheduleCount = 0;

        function addScheduleRow() {
            scheduleCount++;
            const container = document.getElementById('schedulesContainer');
            
            const row = document.createElement('div');
            row.className = 'grid grid-cols-1 md:grid-cols-5 gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200';
            row.id = `schedule-${scheduleCount}`;
            
            row.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Día *</label>
                    <select name="schedules[${scheduleCount}][day]" required class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800">
                        <option value="">Seleccionar...</option>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Hora Inicio *</label>
                    <input type="time" name="schedules[${scheduleCount}][start_time]" required class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Hora Fin *</label>
                    <input type="time" name="schedules[${scheduleCount}][end_time]" required class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Aula *</label>
                    <input type="text" name="schedules[${scheduleCount}][aula]" required placeholder="Ej: 301-A" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-800" />
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removeScheduleRow(${scheduleCount})" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>
                </div>
            `;
            
            container.appendChild(row);
        }

        function removeScheduleRow(id) {
            const row = document.getElementById(`schedule-${id}`);
            if (row) {
                row.remove();
            }
        }

        // Add one schedule row by default
        window.addEventListener('DOMContentLoaded', function() {
            addScheduleRow();
        });

        // Form validation
        document.getElementById('groupForm').addEventListener('submit', function(e) {
            const schedulesContainer = document.getElementById('schedulesContainer');
            const scheduleRows = schedulesContainer.querySelectorAll('[id^="schedule-"]');
            
            if (scheduleRows.length === 0) {
                e.preventDefault();
                alert('Debes agregar al menos un horario para el grupo.');
                return false;
            }
        });
    </script>
</x-layouts.app>
