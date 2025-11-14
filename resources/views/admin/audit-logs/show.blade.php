<x-layouts.app :title="__('Detalle de Auditoría')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Detalle del Registro de Auditoría</h2>
                <p class="text-gray-600 mt-1">Información completa del registro</p>
            </div>
            <a href="{{ route('admin.audit-logs.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                ← Volver
            </a>
        </div>
        <div class="max-w-4xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-600">Fecha y Hora</h3>
                            <p class="mt-1 text-lg font-semibold text-black">{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-600">Usuario</h3>
                            <p class="mt-1 text-lg font-semibold text-black">
                                {{ $auditLog->user ? $auditLog->user->name : 'Sistema' }}
                            </p>
                            @if($auditLog->user)
                                <p class="text-sm text-gray-700">{{ $auditLog->user->email }}</p>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Acción</h3>
                            <p class="mt-1">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    @if(str_contains($auditLog->action, 'created')) bg-green-100 text-green-800
                                    @elseif(str_contains($auditLog->action, 'updated')) bg-blue-100 text-blue-800
                                    @elseif(str_contains($auditLog->action, 'deleted')) bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $auditLog->action }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-600">Modelo Afectado</h3>
                            <p class="mt-1 text-lg font-semibold text-black">
                                {{ $auditLog->model_type ? class_basename($auditLog->model_type) : 'N/A' }}
                                @if($auditLog->model_id)
                                    <span class="text-indigo-600">#{{ $auditLog->model_id }}</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-600">Dirección IP</h3>
                            <p class="mt-1 text-lg font-mono font-semibold text-black">{{ $auditLog->ip_address ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-600">User Agent</h3>
                            <p class="mt-1 text-sm text-black">{{ Str::limit($auditLog->user_agent ?? 'N/A', 50) }}</p>
                        </div>

                        @if($auditLog->old_values)
                            <div class="md:col-span-2">
                                <h3 class="text-sm font-medium text-gray-600 mb-2">Valores Anteriores</h3>
                                <div class="bg-gray-100 p-4 rounded-md border border-gray-300">
                                    <pre class="text-xs text-black overflow-x-auto">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        @endif

                        @if($auditLog->new_values)
                            <div class="md:col-span-2">
                                <h3 class="text-sm font-medium text-gray-600 mb-2">Valores Nuevos</h3>
                                <div class="bg-gray-100 p-4 rounded-md border border-gray-300">
                                    <pre class="text-xs text-black overflow-x-auto">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
