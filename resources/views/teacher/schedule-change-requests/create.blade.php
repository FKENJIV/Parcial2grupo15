<x-layouts.app :title="__('Solicitar Cambio de Horario')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">{{ __('Solicitar Cambio de Horario') }}</h2>
        </div>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('teacher.schedule-change-requests.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="schedule_id" class="block text-sm font-medium text-gray-700">Horario a Cambiar *</label>
                            <select name="schedule_id" id="schedule_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('schedule_id') border-red-500 @enderror">
                                <option value="">Seleccione un horario</option>
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->group->subjectModel ? $schedule->group->subjectModel->name : $schedule->group->subject }} - 
                                        {{ $schedule->group->group_name }} - 
                                        {{ $schedule->day_of_week }} 
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} 
                                        ({{ $schedule->aula }})
                                    </option>
                                @endforeach
                            </select>
                            @error('schedule_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                            <h3 class="font-semibold text-blue-900 mb-3">Nuevo Horario Propuesto</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="new_day_of_week" class="block text-sm font-medium text-gray-700">Día *</label>
                                    <select name="new_day_of_week" id="new_day_of_week" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Seleccione un día</option>
                                        <option value="Lunes" {{ old('new_day_of_week') == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                                        <option value="Martes" {{ old('new_day_of_week') == 'Martes' ? 'selected' : '' }}>Martes</option>
                                        <option value="Miércoles" {{ old('new_day_of_week') == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                                        <option value="Jueves" {{ old('new_day_of_week') == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                                        <option value="Viernes" {{ old('new_day_of_week') == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                                        <option value="Sábado" {{ old('new_day_of_week') == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                                    </select>
                                    @error('new_day_of_week')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="new_aula" class="block text-sm font-medium text-gray-700">Aula (opcional)</label>
                                    <input type="text" name="new_aula" id="new_aula" value="{{ old('new_aula') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="Ej: A-101">
                                    @error('new_aula')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="new_start_time" class="block text-sm font-medium text-gray-700">Hora de Inicio *</label>
                                    <input type="time" name="new_start_time" id="new_start_time" value="{{ old('new_start_time') }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('new_start_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="new_end_time" class="block text-sm font-medium text-gray-700">Hora de Fin *</label>
                                    <input type="time" name="new_end_time" id="new_end_time" value="{{ old('new_end_time') }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('new_end_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Razón del Cambio *</label>
                            <textarea name="reason" id="reason" rows="4" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('reason') border-red-500 @enderror"
                                      placeholder="Explique detalladamente por qué necesita cambiar el horario (mínimo 10 caracteres)...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Esta información será revisada por el administrador.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('teacher.schedule-change-requests.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Enviar Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-layouts.app>
