<x-layouts.app :title="__('Crear Grupo y Asignar Horario')">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Crear Grupo</h2>
                <p class="text-gray-600 mt-1">CU4: Definir nuevo grupo, cupo de alumnos y seleccionar bloques horarios (3 horas/3 días)</p>
            </div>
            <a href="{{ route('teacher.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al Panel
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('teacher.groups.store') }}" method="POST" class="space-y-6" id="groupForm">
        @csrf

        <!-- Group Information -->
        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Grupo</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Materia / Asignatura *</label>
                    <input type="text" name="subject" required placeholder="Ej: Sistemas de Información 1" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Código del Grupo *</label>
                    <input type="text" name="code" required placeholder="Ej: SC, A, B1" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Cupo Máximo de Alumnos *</label>
                    <input type="number" name="max_students" required min="1" max="100" placeholder="Ej: 30" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    <p class="text-xs text-gray-500 mt-1">Número máximo de estudiantes permitidos en el grupo</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Aula / Ambiente *</label>
                    <input type="text" name="classroom" required placeholder="Ej: Aula 301, Lab 4" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-600 mb-2">Descripción / Notas</label>
                <textarea name="description" rows="3" placeholder="Información adicional sobre el grupo..." class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
        </section>

        <!-- Schedule Selection -->
        <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Selección de Horarios</h3>
                    <p class="text-sm text-gray-600 mt-1">Selecciona de 1 a 3 horas por día, en máximo 3 días diferentes</p>
                </div>
                <div class="text-sm text-indigo-600 font-medium" id="selectedCount">
                    Seleccionados: <span id="count">0</span> bloques
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b-2 border-indigo-200">
                            <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Hora</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700">Lunes</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700">Martes</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700">Miércoles</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700">Jueves</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700">Viernes</th>
                            <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700">Sábado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach(['07:00-08:00', '08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-13:00', '14:00-15:00', '15:00-16:00', '16:00-17:00', '17:00-18:00', '18:00-19:00', '19:00-20:00', '20:00-21:00'] as $index => $time)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-sm text-gray-600 font-medium">{{ $time }}</td>
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $dayEn)
                            @php
                                $dayEs = ['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 'thursday' => 'Jueves', 'friday' => 'Viernes', 'saturday' => 'Sábado'][$dayEn];
                                $timeBlock = 7 + $index;
                            @endphp
                            <td class="py-3 px-4 text-center">
                                <label class="inline-flex items-center justify-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="schedules_temp[]" 
                                           value="{{ $dayEn }}_{{ $timeBlock }}" 
                                           data-day="{{ $dayEn }}"
                                           data-time="{{ $timeBlock }}"
                                           class="schedule-checkbox w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer" />
                                </label>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 text-indigo-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-indigo-700">
                        <p class="font-medium">Restricciones:</p>
                        <ul class="list-disc list-inside mt-1 space-y-1">
                            <li>Puedes seleccionar de 1 a 3 horas por día</li>
                            <li>Máximo 3 días diferentes en la semana</li>
                            <li>Cada bloque representa 1 hora de clase</li>
                            <li>Ejemplo: 2 horas el lunes, 2 horas el miércoles, 1 hora el viernes = válido</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed" id="submitButton" disabled>
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Crear Grupo y Asignar Horarios
            </button>
            <a href="{{ route('teacher.schedules') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</x-layouts.app>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('groupForm');
    const checkboxes = document.querySelectorAll('.schedule-checkbox');
    const countSpan = document.getElementById('count');
    const submitButton = document.getElementById('submitButton');
    
    function updateSelection() {
        const selected = document.querySelectorAll('.schedule-checkbox:checked');
        const count = selected.length;
        const dayHours = {};
        
        selected.forEach(cb => {
            const day = cb.dataset.day;
            if (!dayHours[day]) {
                dayHours[day] = 0;
            }
            dayHours[day]++;
        });
        
        const daysCount = Object.keys(dayHours).length;
        countSpan.textContent = count;
        
        // Check if all days have 1-3 hours
        let validHoursPerDay = true;
        for (const day in dayHours) {
            if (dayHours[day] < 1 || dayHours[day] > 3) {
                validHoursPerDay = false;
                break;
            }
        }
        
        // Enable submit if: at least 1 block selected, max 3 days, 1-3 hours per day
        const isValid = count >= 1 && daysCount <= 3 && validHoursPerDay;
        submitButton.disabled = !isValid;
        
        // Disable days that already have 3 hours
        checkboxes.forEach(cb => {
            const day = cb.dataset.day;
            if (!cb.checked && dayHours[day] >= 3) {
                cb.disabled = true;
                cb.parentElement.classList.add('opacity-50', 'cursor-not-allowed');
            } else if (!cb.checked && daysCount >= 3 && !dayHours[day]) {
                // Disable days not selected if we already have 3 different days
                cb.disabled = true;
                cb.parentElement.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                cb.disabled = false;
                cb.parentElement.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelection);
    });
    
    // Before submit, create hidden inputs with the correct format
    form.addEventListener('submit', function(e) {
        // Remove any previous schedule inputs
        const oldInputs = form.querySelectorAll('input[name^="schedules["]');
        oldInputs.forEach(input => input.remove());
        
        // Get selected schedules
        const selected = document.querySelectorAll('.schedule-checkbox:checked');
        
        selected.forEach((cb, index) => {
            const day = cb.dataset.day;
            const time = cb.dataset.time;
            
            // Create hidden inputs for each schedule
            const dayInput = document.createElement('input');
            dayInput.type = 'hidden';
            dayInput.name = `schedules[${index}][day]`;
            dayInput.value = day;
            form.appendChild(dayInput);
            
            const timeInput = document.createElement('input');
            timeInput.type = 'hidden';
            timeInput.name = `schedules[${index}][time_block]`;
            timeInput.value = time;
            form.appendChild(timeInput);
        });
    });
});
</script>
