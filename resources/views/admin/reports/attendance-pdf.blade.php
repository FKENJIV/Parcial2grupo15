<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Asistencias</title>
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
        .badge-present { background: #c6f6d5; color: #22543d; }
        .badge-absent { background: #fed7d7; color: #742a2a; }
        .badge-late { background: #feebc8; color: #7c2d12; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Asistencias</h1>
        <p>Sistema de Gestión Académica</p>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-box">
        <strong>Período:</strong> {{ \Carbon\Carbon::parse($date_from)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($date_to)->format('d/m/Y') }}<br>
        @if($teacher)
            <strong>Docente:</strong> {{ $teacher->name }}<br>
        @else
            <strong>Docente:</strong> Todos<br>
        @endif
        <strong>Total de registros:</strong> {{ $attendances->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Docente</th>
                <th>Materia</th>
                <th>Grupo</th>
                <th>Aula</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->registered_at->format('d/m/Y') }}</td>
                    <td>{{ $attendance->teacher->name }}</td>
                    <td>{{ $attendance->schedule->group->subjectModel ? $attendance->schedule->group->subjectModel->name : $attendance->schedule->group->subject }}</td>
                    <td>{{ $attendance->schedule->group->group_name }}</td>
                    <td>{{ $attendance->aula }}</td>
                    <td>
                        <span class="badge badge-{{ $attendance->status == 'present' ? 'present' : ($attendance->status == 'absent' ? 'absent' : 'late') }}">
                            {{ $attendance->status == 'present' ? 'Presente' : ($attendance->status == 'absent' ? 'Ausente' : 'Tarde') }}
                        </span>
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
