<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Exports\ScheduleExport;
use App\Exports\WorkloadExport;
use App\Exports\AbsenceExport;
use App\Exports\TeacherExport;

class ReportController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_teachers' => User::whereIn('role', ['teacher', 'docente'])->count(),
            'total_groups' => Group::count(),
            'absent_count' => Attendance::where('status', 'absent')->count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    /**
     * Reporte estadístico de carga horaria por docente
     */
    public function workloadReport(Request $request)
    {
        $teachers = User::whereIn('role', ['teacher', 'docente'])
            ->withCount('groups')
            ->with(['groups.schedules'])
            ->get()
            ->map(function ($teacher) {
                $totalHours = $teacher->groups->sum(function ($group) {
                    return $group->schedules->count() * 2; // Asumiendo 2 horas por bloque
                });
                
                return [
                    'name' => $teacher->name,
                    'groups_count' => $teacher->groups_count,
                    'total_hours' => $totalHours,
                ];
            });

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.workload-pdf', compact('teachers'));
            return $pdf->download('reporte-carga-horaria-' . date('Y-m-d') . '.pdf');
        }

        if ($request->format === 'excel') {
            return Excel::download(new WorkloadExport($teachers), 'reporte-carga-horaria-' . date('Y-m-d') . '.xlsx');
        }

        return view('admin.reports.workload-html', compact('teachers'));
    }

    /**
     * Reporte de ausencias por docente
     */
    public function absenceReport(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'format' => 'required|in:pdf,html,excel',
        ]);

        $absences = Attendance::with(['teacher', 'schedule.group.subjectModel'])
            ->where('status', 'absent')
            ->whereBetween('registered_at', [$validated['date_from'], $validated['date_to']])
            ->orderBy('registered_at', 'desc')
            ->get();

        $data = [
            'absences' => $absences,
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
            'total_absences' => $absences->count(),
        ];

        if ($validated['format'] === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.absence-pdf', $data);
            return $pdf->download('reporte-ausencias-' . date('Y-m-d') . '.pdf');
        }

        if ($validated['format'] === 'excel') {
            return Excel::download(new AbsenceExport($absences), 'reporte-ausencias-' . date('Y-m-d') . '.xlsx');
        }

        return view('admin.reports.absence-html', $data);
    }

    public function attendanceReport(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
            'group_id' => 'nullable|exists:groups,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'format' => 'required|in:pdf,html,excel',
        ]);

        $query = Attendance::with(['teacher', 'schedule.group.subjectModel']);

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('group_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('group_id', $request->group_id);
            });
        }

        $query->whereBetween('registered_at', [$validated['date_from'], $validated['date_to']]);

        $attendances = $query->orderBy('registered_at', 'desc')->get();

        $data = [
            'attendances' => $attendances,
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
            'teacher' => $request->filled('teacher_id') ? User::find($request->teacher_id) : null,
            'group' => $request->filled('group_id') ? Group::find($request->group_id) : null,
        ];

        if ($validated['format'] === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.attendance-pdf', $data);
            return $pdf->download('reporte-asistencia-' . date('Y-m-d') . '.pdf');
        }

        if ($validated['format'] === 'excel') {
            return Excel::download(new AttendanceExport($attendances), 'reporte-asistencia-' . date('Y-m-d') . '.xlsx');
        }

        return view('admin.reports.attendance-html', $data);
    }

    public function scheduleReport(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
            'day_of_week' => 'nullable|string',
            'format' => 'required|in:pdf,html,excel',
        ]);

        $query = Schedule::with(['group.teacher', 'group.subjectModel']);

        if ($request->filled('teacher_id')) {
            $query->whereHas('group', function ($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }

        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();

        $data = [
            'schedules' => $schedules,
            'teacher' => $request->filled('teacher_id') ? User::find($request->teacher_id) : null,
            'day_of_week' => $request->day_of_week ?? 'Todos',
        ];

        if ($validated['format'] === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.schedule-pdf', $data);
            return $pdf->download('reporte-horarios-' . date('Y-m-d') . '.pdf');
        }

        if ($validated['format'] === 'excel') {
            return Excel::download(new ScheduleExport($schedules), 'reporte-horarios-' . date('Y-m-d') . '.xlsx');
        }

        return view('admin.reports.schedule-html', $data);
    }

    public function teacherReport(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:active,inactive',
            'type' => 'nullable|in:titular,invitado,auxiliar',
            'format' => 'required|in:pdf,html,excel',
        ]);

        $query = User::whereIn('role', ['teacher', 'docente'])->with('subjects');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $teachers = $query->orderBy('name')->get();

        $data = ['teachers' => $teachers];

        if ($validated['format'] === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.teacher-pdf', $data);
            return $pdf->download('reporte-docentes-' . date('Y-m-d') . '.pdf');
        }

        if ($validated['format'] === 'excel') {
            return Excel::download(new TeacherExport($teachers), 'reporte-docentes-' . date('Y-m-d') . '.xlsx');
        }

        return view('admin.reports.teacher-html', $data);
    }
}
