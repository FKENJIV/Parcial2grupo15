<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TeacherExport implements FromCollection, WithHeadings, WithMapping
{
    protected $teachers;

    public function __construct($teachers)
    {
        $this->teachers = $teachers;
    }

    public function collection()
    {
        return $this->teachers;
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Email',
            'Tipo',
            'Estado',
            'Materias'
        ];
    }

    public function map($teacher): array
    {
        return [
            $teacher->name,
            $teacher->email,
            ucfirst($teacher->type ?? 'N/A'),
            $teacher->status === 'active' ? 'Activo' : 'Inactivo',
            $teacher->subjects && $teacher->subjects->count() > 0 
                ? $teacher->subjects->pluck('name')->join(', ') 
                : '-'
        ];
    }
}
