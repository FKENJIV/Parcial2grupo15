<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Docente',
            'Grupo',
            'Materia',
            'Estado',
            'Observaciones'
        ];
    }

    public function map($attendance): array
    {
        return [
            date('d/m/Y H:i', strtotime($attendance->registered_at)),
            $attendance->teacher->name,
            $attendance->schedule->group->name ?? 'N/A',
            $attendance->schedule->group->subjectModel->name ?? 'N/A',
            $attendance->status === 'present' ? 'Presente' : 'Ausente',
            $attendance->observations ?? '-'
        ];
    }
}
