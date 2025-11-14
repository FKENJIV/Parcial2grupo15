<x-layouts.app :title="__('Editar Incidente')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Editar Incidente</h2>
        </div>

        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.incidents.update', $incident) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Aula *</label>
                        <input type="text" name="aula" value="{{ old('aula', $incident->aula) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Fecha *</label>
                        <input type="date" name="incident_date" value="{{ old('incident_date', $incident->incident_date->format('Y-m-d')) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tipo *</label>
                        <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="daño" {{ old('type', $incident->type) == 'daño' ? 'selected' : '' }}>Daño</option>
                            <option value="mantenimiento" {{ old('type', $incident->type) == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="limpieza" {{ old('type', $incident->type) == 'limpieza' ? 'selected' : '' }}>Limpieza</option>
                            <option value="otro" {{ old('type', $incident->type) == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Descripción *</label>
                        <textarea name="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $incident->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Estado *</label>
                        <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="reportado" {{ old('status', $incident->status) == 'reportado' ? 'selected' : '' }}>Reportado</option>
                            <option value="en_proceso" {{ old('status', $incident->status) == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="resuelto" {{ old('status', $incident->status) == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Asignar a</label>
                        <select name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Sin asignar</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $incident->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Notas de Resolución</label>
                        <textarea name="resolution_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('resolution_notes', $incident->resolution_notes) }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('admin.incidents.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
