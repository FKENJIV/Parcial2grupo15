<x-layouts.app :title="__('Reportes')">
    <div class="py-6 px-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Generación de Reportes</h2>
            <p class="text-gray-600 mt-1">Reportes estadísticos y exportación en PDF y Excel</p>
        </div>

        <!-- Estadísticas Generales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Docentes</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $stats['total_teachers'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Grupos</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['total_groups'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Ausencias Totales</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['absent_count'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Reporte de Asistencias -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-black">Reporte de Asistencias</h3>
                    <p class="text-sm text-gray-600 mb-4">Genera reportes de asistencia con filtros personalizados</p>
                    <form method="POST" action="{{ route('admin.reports.attendance') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Docente (opcional)</label>
                                <select name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-black">
                                    <option value="">Todos los docentes</option>
                                    @foreach(\App\Models\User::whereIn('role', ['teacher', 'docente'])->orderBy('name')->get() as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde *</label>
                                <input type="date" name="date_from" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-black">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta *</label>
                                <input type="date" name="date_to" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-black">
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="format" value="pdf" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Descargar PDF
                            </button>
                            <button type="submit" name="format" value="excel" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Descargar Excel
                            </button>
                            <button type="submit" name="format" value="html" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ver en Pantalla
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reporte de Horarios -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-black">Reporte de Horarios</h3>
                    <p class="text-sm text-gray-600 mb-4">Genera reportes de horarios por docente o día</p>
                    <form method="POST" action="{{ route('admin.reports.schedule') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Docente (opcional)</label>
                                <select name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-black">
                                    <option value="">Todos los docentes</option>
                                    @foreach(\App\Models\User::whereIn('role', ['teacher', 'docente'])->orderBy('name')->get() as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Día de la Semana (opcional)</label>
                                <select name="day_of_week" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-black">
                                    <option value="">Todos los días</option>
                                    <option value="Lunes">Lunes</option>
                                    <option value="Martes">Martes</option>
                                    <option value="Miércoles">Miércoles</option>
                                    <option value="Jueves">Jueves</option>
                                    <option value="Viernes">Viernes</option>
                                    <option value="Sábado">Sábado</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="format" value="pdf" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Descargar PDF
                            </button>
                            <button type="submit" name="format" value="excel" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Descargar Excel
                            </button>
                            <button type="submit" name="format" value="html" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ver en Pantalla
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reporte de Carga Horaria -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-black">Reporte de Carga Horaria por Docente</h3>
                    <p class="text-sm text-gray-600 mb-4">Muestra la cantidad de grupos y horas asignadas a cada docente</p>
                    <form method="POST" action="{{ route('admin.reports.workload') }}" class="space-y-4">
                        @csrf
                        <div class="flex space-x-3">
                            <button type="submit" name="format" value="pdf" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Descargar PDF
                            </button>
                            <button type="submit" name="format" value="excel" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Descargar Excel
                            </button>
                            <button type="submit" name="format" value="html" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ver en Pantalla
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reporte de Ausencias -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-black">Reporte de Ausencias</h3>
                    <p class="text-sm text-gray-600 mb-4">Lista todas las ausencias de docentes en un período</p>
                    <form method="POST" action="{{ route('admin.reports.absence') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde *</label>
                                <input type="date" name="date_from" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-black">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta *</label>
                                <input type="date" name="date_to" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-black">
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="format" value="pdf" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Descargar PDF
                            </button>
                            <button type="submit" name="format" value="excel" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Descargar Excel
                            </button>
                            <button type="submit" name="format" value="html" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ver en Pantalla
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reporte de Docentes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-black">Reporte de Docentes</h3>
                    <p class="text-sm text-gray-600 mb-4">Lista completa de docentes con sus datos</p>
                    <form method="POST" action="{{ route('admin.reports.teacher') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Estado (opcional)</label>
                                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-black">
                                    <option value="">Todos</option>
                                    <option value="active">Activos</option>
                                    <option value="inactive">Inactivos</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo (opcional)</label>
                                <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todos</option>
                                    <option value="titular">Titular</option>
                                    <option value="invitado">Invitado</option>
                                    <option value="auxiliar">Auxiliar</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="format" value="pdf" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Descargar PDF
                            </button>
                            <button type="submit" name="format" value="excel" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Descargar Excel
                            </button>
                            <button type="submit" name="format" value="html" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ver en Pantalla
                            </button>
                        </div>
                    </form>
                </div>
            </div>

    </div>
</x-layouts.app>
