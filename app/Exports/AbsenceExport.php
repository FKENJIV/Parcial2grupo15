<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsenceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $absences;

    public function __construct($absences)
    {
        $this->absences = $absences;
    }

    public function collection()
    {
        return $this->absences;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Docente',
            'Grupo',
            'Materia',
            'Observaciones'
        ];
    }

    public function map($absence): array
    {
        return [
            date('d/m/Y', strtotime($absence->registered_at)),
            $absence->teacher->name,
            $absence->schedule->group->name ?? 'N/A',
            $absence->schedule->group->subjectModel->name ?? 'N/A',
            $absence->observations ?? '-'
        ];
    }
}
