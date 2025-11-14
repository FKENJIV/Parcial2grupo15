<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WorkloadExport implements FromCollection, WithHeadings
{
    protected $teachers;

    public function __construct($teachers)
    {
        $this->teachers = $teachers;
    }

    public function collection()
    {
        return collect($this->teachers)->map(function ($teacher) {
            return [
                'Docente' => $teacher['name'],
                'Grupos Asignados' => $teacher['groups_count'],
                'Horas Totales' => $teacher['total_hours']
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Docente',
            'Grupos Asignados',
            'Horas Totales'
        ];
    }
}
