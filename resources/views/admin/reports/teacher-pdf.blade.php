<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Docentes</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        .info-box { background: #f5f5f5; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #4a5568; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 10px; font-weight: bold; }
        .badge-active { background: #c6f6d5; color: #22543d; }
        .badge-inactive { background: #fed7d7; color: #742a2a; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Docentes</h1>
        <p>Sistema de Gestión Académica</p>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-box">
        <strong>Total de docentes:</strong> {{ $teachers->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Código</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Especialidades</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->code }}</td>
                    <td>{{ ucfirst($teacher->type ?? 'N/A') }}</td>
                    <td>
                        <span class="badge badge-{{ $teacher->status == 'active' ? 'active' : 'inactive' }}">
                            {{ $teacher->status == 'active' ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        @if($teacher->subjects->count() > 0)
                            {{ $teacher->subjects->pluck('name')->join(', ') }}
                        @else
                            {{ $teacher->specialties ?? 'N/A' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Este documento fue generado automáticamente por el Sistema de Gestión Académica</p>
    </div>
</body>
</html>
