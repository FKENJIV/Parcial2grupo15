<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScheduleExport implements FromCollection, WithHeadings, WithMapping
{
    protected $schedules;

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function collection()
    {
        return $this->schedules;
    }

    public function headings(): array
    {
        return [
            'Día',
            'Hora Inicio',
            'Hora Fin',
            'Docente',
            'Grupo',
            'Materia',
            'Aula'
        ];
    }

    public function map($schedule): array
    {
        return [
            $schedule->day_of_week,
            substr($schedule->start_time, 0, 5),
            substr($schedule->end_time, 0, 5),
            $schedule->group->teacher->name ?? 'N/A',
            $schedule->group->name,
            $schedule->group->subjectModel->name ?? 'N/A',
            $schedule->classroom ?? '-'
        ];
    }
}
