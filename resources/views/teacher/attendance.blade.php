<x-layouts.app :title="__('Registrar Asistencia Docente')">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Registrar Asistencia</h2>
                <p class="text-gray-600 mt-1">CU5: Registrar asistencia a clase programada</p>
            </div>
            <a href="{{ route('teacher.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al Panel
            </a>
        </div>
    </div>

    <!-- Current Time and Date -->
    <section class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl shadow-lg p-6 mb-6 text-white">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm opacity-90">Fecha Actual</p>
                    <p class="text-lg font-semibold" id="currentDate">{{ now()->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm opacity-90">Hora Actual</p>
                    <p class="text-lg font-semibold" id="currentTime">{{ now()->format('H:i:s') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm opacity-90">Docente</p>
                    <p class="text-lg font-semibold">{{ auth()->user()->name }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Today's Classes -->
    <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Clases Programadas para Hoy</h3>
        
        @if($todaySchedules->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <svg class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-lg mb-2">No tienes clases programadas para hoy</p>
                <p class="text-sm">Revisa tu horario o crea un nuevo grupo</p>
            </div>
        @else
            <div class="space-y-4">
                @php
                    $currentHour = now()->hour;
                    $currentMinute = now()->minute;
                    $currentTime = $currentHour * 60 + $currentMinute; // en minutos
                @endphp

                @foreach($todaySchedules as $schedule)
                    @php
                        $classStartHour = $schedule->time_block;
                        $classEndHour = $schedule->time_block + 1;
                        $classStartTime = $classStartHour * 60; // en minutos
                        $classEndTime = $classEndHour * 60; // en minutos
                        
                        // Verificar si ya hay asistencia registrada hoy
                        $alreadyAttended = $attendances->first(function($att) use ($schedule) {
                            return $att->schedule_id === $schedule->id && 
                                   $att->created_at->isToday();
                        });
                        
                        // Determinar estado de la clase
                        $isActive = $currentTime >= $classStartTime && $currentTime < $classEndTime;
                        $isPast = $currentTime >= $classEndTime;
                        $isFuture = $currentTime < $classStartTime;
                        
                        // Auto determinar si es "late" (después de 15 minutos del inicio)
                        $lateThreshold = $classStartTime + 15; // 15 minutos después del inicio
                        $isLate = $currentTime >= $lateThreshold && $currentTime < $classEndTime;
                    @endphp

                    <div class="p-4 border-2 rounded-xl transition-all
                        @if($alreadyAttended) 
                            border-green-200 bg-green-50
                        @elseif($isPast) 
                            border-red-200 bg-red-50
                        @elseif($isActive) 
                            border-indigo-400 bg-indigo-50
                        @else 
                            border-gray-200 bg-white
                        @endif">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    @if($alreadyAttended)
                                        <span class="px-3 py-1 bg-green-200 text-green-800 text-sm font-semibold rounded-full">✓ Asistencia Registrada</span>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                            {{ sprintf('%02d:00 - %02d:00', $classStartHour, $classEndHour) }}
                                        </span>
                                        @if($alreadyAttended->status === 'late')
                                            <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">Retrasado</span>
                                        @endif
                                    @elseif($isPast)
                                        <span class="px-3 py-1 bg-red-200 text-red-800 text-sm font-semibold rounded-full">⏰ Clase Finalizada</span>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                            {{ sprintf('%02d:00 - %02d:00', $classStartHour, $classEndHour) }}
                                        </span>
                                    @elseif($isActive)
                                        <span class="px-3 py-1 bg-indigo-200 text-indigo-800 text-sm font-semibold rounded-full animate-pulse">🔴 EN CURSO</span>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                            {{ sprintf('%02d:00 - %02d:00', $classStartHour, $classEndHour) }}
                                        </span>
                                        @if($isLate)
                                            <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">⚠️ Marcado tardío</span>
                                        @endif
                                    @else
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">⏳ Próxima Clase</span>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                            {{ sprintf('%02d:00 - %02d:00', $classStartHour, $classEndHour) }}
                                        </span>
                                    @endif
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-1">{{ $schedule->group->subject }} - {{ $schedule->group->code }}</h4>
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <span class="flex items-center gap-1">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $schedule->group->classroom }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $schedule->group->max_students }} estudiantes
                                    </span>
                                </div>
                                @if($alreadyAttended && $alreadyAttended->notes)
                                    <p class="text-sm text-gray-600 mt-2 italic">Nota: {{ $alreadyAttended->notes }}</p>
                                @endif
                            </div>
                            <div>
                                @if($alreadyAttended)
                                    <div class="text-center">
                                        <svg class="h-12 w-12 text-green-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs text-green-700 mt-1">{{ $alreadyAttended->created_at->format('H:i') }}</p>
                                    </div>
                                @elseif($isPast)
                                    <button disabled class="px-6 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        No Disponible
                                    </button>
                                @elseif($isActive)
                                    <button onclick="openAttendanceModal('{{ $schedule->id }}', '{{ $schedule->group->subject }} - {{ $schedule->group->code }}', '{{ sprintf('%02d:00 - %02d:00', $classStartHour, $classEndHour) }}', '{{ $schedule->group->classroom }}', {{ $isLate ? 'true' : 'false' }})" 
                                        class="px-6 py-3 {{ $isLate ? 'bg-orange-600 hover:bg-orange-700' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white rounded-lg font-medium transition-colors">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $isLate ? 'Registrar (Tardío)' : 'Registrar Asistencia' }}
                                    </button>
                                @else
                                    <button disabled class="px-6 py-3 bg-gray-200 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Aún no disponible
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Attendance History -->
    <section class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Historial de Asistencias</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b-2 border-indigo-200">
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Fecha</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Hora</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Materia / Grupo</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Aula</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Estado</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Hora Registro</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-sm text-gray-800">
                                {{ $attendance->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ sprintf('%02d:00 - %02d:00', $attendance->schedule->time_block, $attendance->schedule->time_block + 1) }}
                            </td>
                            <td class="py-3 px-4 text-sm">
                                <p class="font-medium text-gray-800">{{ $attendance->schedule->group->subject }}</p>
                                <p class="text-xs text-gray-500">{{ $attendance->schedule->group->code }}</p>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ $attendance->schedule->group->classroom }}
                            </td>
                            <td class="py-3 px-4 text-sm">
                                @if($attendance->status === 'present')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">✓ Presente</span>
                                @elseif($attendance->status === 'late')
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">⏰ Retrasado</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">✗ Ausente</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ $attendance->created_at->format('H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                No hay registros de asistencia todavía
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>

<!-- Attendance Modal -->
<div id="attendanceModal" class="fixed inset-0 bg-indigo-900 bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="relative w-full max-w-[90vw] sm:max-w-lg mx-2 sm:mx-4">
        <div class="relative bg-white shadow-[0_30px_80px_rgba(2,6,23,0.12)] border-2 border-indigo-200
            rounded-none sm:rounded-xl max-h-[90vh] overflow-auto">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-indigo-100 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-indigo-700">Confirmar Asistencia</h3>
                    <button onclick="closeAttendanceModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <form id="attendanceForm" action="{{ route('teacher.attendance.store') }}" method="POST" class="p-4 sm:p-6">
                @csrf
                <input type="hidden" name="schedule_id" id="scheduleId">
                <input type="hidden" name="status" value="present">

                <div class="space-y-4 mb-6">
                    <div class="p-4 bg-indigo-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Materia / Grupo</p>
                        <p class="font-semibold text-gray-800" id="modalSubject">-</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Horario</p>
                            <p class="font-semibold text-gray-800" id="modalTime">-</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Aula</p>
                            <p class="font-semibold text-gray-800" id="modalClassroom">-</p>
                        </div>
                    </div>

                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-green-700">
                                <p class="font-medium">Registrando asistencia</p>
                                <p class="mt-1">Al confirmar, se registrará tu asistencia a esta clase</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Confirmar Asistencia
                    </button>
                    <button type="button" onclick="closeAttendanceModal()" class="px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Update time every second
setInterval(() => {
    const now = new Date();
    const currentTimeEl = document.getElementById('currentTime');
    if (currentTimeEl) {
        currentTimeEl.textContent = now.toLocaleTimeString('es-ES');
    }

    // Auto-refresh page every minute to update class statuses
    if (now.getSeconds() === 0) {
        location.reload();
    }
}, 1000);

function openAttendanceModal(scheduleId, subject, time, classroom, isLate = false) {
    const scheduleIdEl = document.getElementById('scheduleId');
    if (scheduleIdEl) scheduleIdEl.value = scheduleId;

    const modalSubjectEl = document.getElementById('modalSubject');
    if (modalSubjectEl) modalSubjectEl.textContent = subject;

    const modalTimeEl = document.getElementById('modalTime');
    if (modalTimeEl) modalTimeEl.textContent = time;

    const modalClassroomEl = document.getElementById('modalClassroom');
    if (modalClassroomEl) modalClassroomEl.textContent = classroom;

    // Set status based on isLate
    const statusField = document.querySelector('input[name="status"]');
    if (statusField) statusField.value = isLate ? 'late' : 'present';

    // Update message
    const messageDiv = document.querySelector('#attendanceModal .text-green-700');
    if (messageDiv) {
        const parent = messageDiv.parentElement;
        // svg is a sibling of messageDiv inside the same parent
        const iconSvg = parent ? parent.querySelector('svg') : null;

        if (isLate) {
            messageDiv.innerHTML = '<p class="font-medium text-orange-700">Registrando asistencia tardía</p><p class="mt-1 text-orange-600">Has llegado después de la hora de inicio. Se marcará como retrasado.</p>';
            if (parent) {
                parent.classList.remove('bg-green-50', 'border-green-200');
                parent.classList.add('bg-orange-50', 'border-orange-200');
            }
            if (iconSvg) {
                iconSvg.classList.remove('text-green-600');
                iconSvg.classList.add('text-orange-600');
            }
        } else {
            messageDiv.innerHTML = '<p class="font-medium">Registrando asistencia</p><p class="mt-1">Al confirmar, se registrará tu asistencia a esta clase</p>';
            if (parent) {
                parent.classList.remove('bg-orange-50', 'border-orange-200');
                parent.classList.add('bg-green-50', 'border-green-200');
            }
            if (iconSvg) {
                iconSvg.classList.remove('text-orange-600');
                iconSvg.classList.add('text-green-600');
            }
        }
    }

    const modalEl = document.getElementById('attendanceModal');
    if (modalEl) modalEl.classList.remove('hidden');
}

function closeAttendanceModal() {
    const modalEl = document.getElementById('attendanceModal');
    if (modalEl) modalEl.classList.add('hidden');
}

// Close modal when clicking outside
const attendanceModalEl = document.getElementById('attendanceModal');
if (attendanceModalEl) {
    attendanceModalEl.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAttendanceModal();
        }
    });
}
</script>
