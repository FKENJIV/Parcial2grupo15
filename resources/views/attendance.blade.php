<x-layouts.app :title="__('Registro de Asistencia')">
    <div class="min-h-screen bg-white p-6">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Page Header -->
            <div class="relative animate-fade-in-up">
                <div aria-hidden class="absolute -inset-6 rounded-2xl bg-sky-100 opacity-20 blur-[40px] pointer-events-none"></div>
                <div class="relative bg-white rounded-xl shadow-[0_30px_80px_rgba(2,6,23,0.12)] border-2 border-sky-200 ring-1 ring-sky-50 p-6 z-10">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-green-400 to-green-600 shadow-lg">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-sky-700">Registrar Asistencia Docente</h1>
                            <p class="text-sm text-sky-500 mt-1">Confirma tu asistencia en el aula programada</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Date/Time Info -->
            <x-card-shadow class="animate-fade-in-up animation-delay-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Fecha actual</p>
                        <p class="text-xl font-bold text-sky-700">Lunes, 28 de Octubre 2025</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Hora actual</p>
                        <p class="text-xl font-bold text-sky-700" id="currentTime">08:15 AM</p>
                    </div>
                </div>
            </x-card-shadow>

            <!-- Available Classes -->
            <x-card-shadow 
                title="Clases Programadas para Hoy"
                :icon="'<svg class=\'h-6 w-6 text-sky-600\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg>'"
                class="animate-fade-in-up animation-delay-200"
            >
                <div class="space-y-4">
                    <!-- Class 1 - Active -->
                    <div class="p-4 bg-green-50 border-2 border-green-200 rounded-lg shadow-md">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h3 class="font-bold text-green-700">Sistemas de Información 1 - Grupo SC</h3>
                                    <span class="px-2 py-1 bg-green-600 text-white text-xs font-semibold rounded-full">En curso</span>
                                </div>
                                <div class="space-y-1 text-sm text-green-600">
                                    <p class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        08:00 - 09:00
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        Aula: 301-A
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form class="space-y-4 pt-3 border-t border-green-200">
                            <div class="bg-white p-3 rounded-lg border border-green-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar ubicación</label>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>¿Estás en el Aula 301-A?</span>
                                </div>
                            </div>

                            <x-button-primary class="w-full">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Registrar Mi Asistencia Ahora
                            </x-button-primary>
                        </form>
                    </div>

                    <!-- Class 2 - Upcoming -->
                    <div class="p-4 bg-sky-50 border-2 border-sky-200 rounded-lg shadow-md opacity-75">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h3 class="font-bold text-sky-700">Base de Datos - Grupo A</h3>
                                    <span class="px-2 py-1 bg-sky-200 text-sky-700 text-xs font-semibold rounded-full">Próxima</span>
                                </div>
                                <div class="space-y-1 text-sm text-sky-600">
                                    <p class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        14:00 - 15:00
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        Aula: Lab-202
                                    </p>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-sky-600 mt-3 flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Disponible para registro 15 minutos antes
                        </p>
                    </div>
                </div>
            </x-card-shadow>

            <!-- Attendance History -->
            <x-card-shadow 
                title="Historial de Asistencia Reciente"
                :icon="'<svg class=\'h-6 w-6 text-sky-600\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4\'/></svg>'"
                class="animate-fade-in-up animation-delay-300"
            >
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                        <div>
                            <p class="text-sm font-medium text-green-700">Viernes, 25 Oct - 08:05 AM</p>
                            <p class="text-xs text-green-600">SI1 - Grupo SC - Aula 301-A</p>
                        </div>
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                        <div>
                            <p class="text-sm font-medium text-green-700">Miércoles, 23 Oct - 08:02 AM</p>
                            <p class="text-xs text-green-600">SI1 - Grupo SC - Aula 301-A</p>
                        </div>
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </x-card-shadow>
        </div>
    </div>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            const timeEl = document.getElementById('currentTime');
            if (timeEl) timeEl.textContent = timeString;
        }
        updateTime();
        setInterval(updateTime, 60000);
    </script>
</x-layouts.app>
