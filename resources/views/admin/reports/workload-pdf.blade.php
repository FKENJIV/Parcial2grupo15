<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Carga Horaria</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #333; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4F46E5; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <h1>📊 Reporte de Carga Horaria por Docente</h1>
    <p style="text-align: center; color: #666;">Generado el {{ date('d/m/Y H:i') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Docente</th>
                <th style="text-align: center;">Grupos Asignados</th>
                <th style="text-align: center;">Horas Totales</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $teacher)
            <tr>
                <td>{{ $teacher['name'] }}</td>
                <td style="text-align: center;">{{ $teacher['groups_count'] }}</td>
                <td style="text-align: center;">{{ $teacher['total_hours'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center; color: #999;">No hay datos disponibles</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f0f0f0;">
                <td>TOTAL</td>
                <td style="text-align: center;">{{ $teachers->sum('groups_count') }}</td>
                <td style="text-align: center;">{{ $teachers->sum('total_hours') }}</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Sistema de Gestión Académica - Reporte de Carga Horaria</p>
    </div>
</body>
</html>
