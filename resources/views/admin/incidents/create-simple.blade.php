<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Incidente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Registrar Nuevo Incidente</h1>
            
            <form method="POST" action="{{ route('admin.incidents.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="aula" class="block text-sm font-medium text-gray-700 mb-2">Aula *</label>
                    <input type="text" name="aula" id="aula" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: A-101">
                </div>

                <div class="mb-4">
                    <label for="incident_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha *</label>
                    <input type="date" name="incident_date" id="incident_date" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo *</label>
                    <select name="type" id="type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione...</option>
                        <option value="daño">Daño</option>
                        <option value="mantenimiento">Mantenimiento</option>
                        <option value="limpieza">Limpieza</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Describa el incidente..."></textarea>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.incidents.index') }}" 
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Registrar Incidente
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
