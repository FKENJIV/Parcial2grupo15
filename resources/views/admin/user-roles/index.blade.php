<x-layouts.app :title="__('Gestión de Roles')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Gestión de Roles y Privilegios</h2>
            <p class="text-gray-600 mt-1">Administra los roles de los usuarios del sistema</p>
        </div>

        <div class="max-w-7xl mx-auto">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Información de Roles -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-blue-900 mb-2">ℹ️ Roles Disponibles</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-white p-3 rounded">
                        <p class="font-semibold text-purple-700">👑 Admin</p>
                        <p class="text-gray-600 text-xs mt-1">Acceso completo al sistema, puede gestionar todo</p>
                    </div>
                    <div class="bg-white p-3 rounded">
                        <p class="font-semibold text-green-700">👨‍🏫 Teacher</p>
                        <p class="text-gray-600 text-xs mt-1">Puede ver horarios, crear grupos y registrar asistencia</p>
                    </div>
                    <div class="bg-white p-3 rounded">
                        <p class="font-semibold text-green-700">👨‍🏫 Docente</p>
                        <p class="text-gray-600 text-xs mt-1">Equivalente a Teacher (compatibilidad)</p>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nombre o email..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                            <select name="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="docente" {{ request('role') == 'docente' ? 'selected' : '' }}>Docente</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Usuarios -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol Actual</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cambiar Rol</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($user->role == 'admin') bg-purple-100 text-purple-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form method="POST" action="{{ route('admin.user-roles.update', $user) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="role" class="text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                                    <option value="docente" {{ $user->role == 'docente' ? 'selected' : '' }}>Docente</option>
                                                </select>
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-1 px-3 rounded">
                                                    Actualizar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No se encontraron usuarios.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
