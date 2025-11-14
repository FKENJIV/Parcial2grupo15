<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ausencias</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #333; font-size: 18px; }
        .info { text-align: center; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #DC2626; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
        .summary { background-color: #FEE2E2; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>❌ Reporte de Ausencias</h1>
    <div class="info">
        <p>Período: {{ date('d/m/Y', strtotime($date_from)) }} - {{ date('d/m/Y', strtotime($date_to)) }}</p>
        <p>Generado el {{ date('d/m/Y H:i') }}</p>
    </div>
    
    <div class="summary">
        <strong>Total de Ausencias: {{ $total_absences }}</strong>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Docente</th>
                <th>Grupo</th>
                <th>Materia</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absences as $absence)
            <tr>
                <td>{{ date('d/m/Y', strtotime($absence->registered_at)) }}</td>
                <td>{{ $absence->teacher->name }}</td>
                <td>{{ $absence->schedule->group->name ?? 'N/A' }}</td>
                <td>{{ $absence->schedule->group->subjectModel->name ?? 'N/A' }}</td>
                <td>{{ $absence->observations ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999;">No hay ausencias registradas en este período</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Sistema de Gestión Académica - Reporte de Ausencias</p>
    </div>
</body>
</html>
