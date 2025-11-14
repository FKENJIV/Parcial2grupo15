<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Horarios</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        .info-box { backgroun
d: #f5f5f5; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #4a5568; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Horarios</h1>
        <p>Sistema de Gestión Académica</p>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-box">
        @if($teacher)
            <strong>Docente:</strong> {{ $teacher->name }}<br>
        @else
            <strong>Docente:</strong> Todos<br>
        @endif
        <strong>Día:</strong> {{ $day_of_week }}<br>
        <strong>Total de horarios:</strong> {{ $schedules->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Día</th>
                <th>Hora</th>
                <th>Materia</th>
                <th>Grupo</th>
                <th>Docente</th>
                <th>Aula</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->day_of_week }}</td>
                    <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                    <td>{{ $schedule->group->subjectModel ? $schedule->group->subjectModel->name : $schedule->group->subject }}</td>
                    <td>{{ $schedule->group->group_name }}</td>
                    <td>{{ $schedule->group->teacher->name }}</td>
                    <td>{{ $schedule->aula }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Este documento fue generado automáticamente por el Sistema de Gestión Académica</p>
    </div>
</body>
</html>
