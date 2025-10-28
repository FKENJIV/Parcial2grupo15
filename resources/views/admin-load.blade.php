<x-layouts.app :title="__('Administrar Carga')">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold">Crear Grupo y Asignar Horario</h2>
        <p class="text-gray-600 mt-1">Define un nuevo grupo, cupo y horarios (3 horas / 3 días)</p>
    </div>

    <!-- Create Group Form -->
    <section class="bg-white rounded-2xl shadow-md p-6 mb-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 shadow-lg">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3  0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-lg text-gray-700">Información del Grupo</h3>
        </div>

        <form class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Materia</label>
                    <select name="materia" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                        <option value="">Seleccionar materia...</option>
                        <option value="si1">Sistemas de Información 1</option>
                        <option value="bd">Base de Datos</option>
                        <option value="prog">Programación</option>
                        <option value="redes">Redes de Computadoras</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Nombre del Grupo</label>
                    <input type="text" name="grupo" required placeholder="Ej: Grupo SC" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Cupo Máximo de Estudiantes</label>
                    <input type="number" name="cupo" required placeholder="Ej: 30" class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Periodo Académico</label>
                    <select name="periodo" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                        <option value="">Seleccionar periodo...</option>
                        <option value="2025-1">2025-1</option>
                        <option value="2025-2">2025-2</option>
                        <option value="2026-1">2026-1</option>
                    </select>
                </div>
            </div>
        </form>
    </section>

    <!-- Schedule Assignment -->
    <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 shadow-lg">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="font-semibold text-lg text-gray-700">Asignación de Horarios</h3>
        </div>

        <div class="space-y-6">
            <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded">
                <div class="flex">
                    <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-indigo-700">
                        <strong>Restricción:</strong> Debes seleccionar exactamente 3 bloques horarios, uno por día (máximo 3 días diferentes).
                    </p>
                </div>
            </div>

            <!-- Schedule Slots -->
            <div id="scheduleSlots" class="space-y-3">
                <!-- Slot 1 -->
                <div class="p-4 bg-white border-2 border-indigo-200 rounded-lg shadow-md">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-indigo-700">Bloque 1</h4>
                        <span class="text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded">Requerido</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Día</label>
                            <select name="dia_1" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                                <option value="">Seleccionar día...</option>
                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miercoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sabado">Sábado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Hora Inicio</label>
                            <input type="time" name="hora_inicio_1" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Hora Fin</label>
                            <input type="time" name="hora_fin_1" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Aula</label>
                        <input type="text" name="aula_1" placeholder="Ej: 301-A" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>
                </div>

                <!-- Slot 2 -->
                <div class="p-4 bg-white border-2 border-indigo-200 rounded-lg shadow-md">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-indigo-700">Bloque 2</h4>
                        <span class="text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded">Requerido</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Día</label>
                            <select name="dia_2" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                                <option value="">Seleccionar día...</option>
                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miercoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sabado">Sábado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Hora Inicio</label>
                            <input type="time" name="hora_inicio_2" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Hora Fin</label>
                            <input type="time" name="hora_fin_2" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Aula</label>
                        <input type="text" name="aula_2" placeholder="Ej: 301-A" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>
                </div>

                <!-- Slot 3 -->
                <div class="p-4 bg-white border-2 border-indigo-200 rounded-lg shadow-md">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-indigo-700">Bloque 3</h4>
                        <span class="text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded">Requerido</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Día</label>
                            <select name="dia_3" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800">
                                <option value="">Seleccionar día...</option>
                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miercoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sabado">Sábado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Hora Inicio</label>
                            <input type="time" name="hora_inicio_3" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Hora Fin</label>
                            <input type="time" name="hora_fin_3" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Aula</label>
                        <input type="text" name="aula_3" placeholder="Ej: 301-A" required class="w-full rounded-lg border border-gray-200 px-4 py-3 bg-white text-gray-800" />
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Crear Grupo y Guardar Horarios
                </button>
                <button type="button" class="px-4 py-3 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors">
                    <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelar
                </button>
            </div>
        </div>
    </section>

    <script>
    // Form validation for group creation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let selectedDays = new Set();

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-300', 'focus:border-red-400', 'focus:ring-red-300');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-300', 'focus:border-red-400', 'focus:ring-red-300');
                }
            });

            // Validate cupo is a positive number
            const cupoField = form.querySelector('[name="cupo"]');
            if (cupoField && cupoField.value) {
                const cupo = parseInt(cupoField.value);
                if (cupo <= 0 || cupo > 50) {
                    cupoField.classList.add('border-red-300', 'focus:border-red-400', 'focus:ring-red-300');
                    isValid = false;
                    alert('El cupo debe ser un número entre 1 y 50.');
                }
            }

            // Validate schedule slots
            for (let i = 1; i <= 3; i++) {
                const diaField = form.querySelector(`[name="dia_${i}"]`);
                const horaInicioField = form.querySelector(`[name="hora_inicio_${i}"]`);
                const horaFinField = form.querySelector(`[name="hora_fin_${i}"]`);

                if (diaField && diaField.value) {
                    selectedDays.add(diaField.value);
                }

                // Validate time logic
                if (horaInicioField && horaFinField && horaInicioField.value && horaFinField.value) {
                    const startTime = new Date(`2000-01-01T${horaInicioField.value}`);
                    const endTime = new Date(`2000-01-01T${horaFinField.value}`);
                    const diffHours = (endTime - startTime) / (1000 * 60 * 60);

                    if (diffHours <= 0) {
                        horaFinField.classList.add('border-red-300', 'focus:border-red-400', 'focus:ring-red-300');
                        isValid = false;
                        alert(`El bloque ${i} tiene una hora de fin anterior o igual a la hora de inicio.`);
                    } else if (diffHours > 2) {
                        alert(`Advertencia: El bloque ${i} dura más de 2 horas. Verifique que sea correcto.`);
                    }
                }
            }

            // Validate exactly 3 different days
            if (selectedDays.size !== 3) {
                isValid = false;
                alert('Debe seleccionar exactamente 3 días diferentes para los bloques horarios.');
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });
    });
    </script>
</x-layouts.app>
