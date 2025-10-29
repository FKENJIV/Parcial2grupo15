<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Group;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendances with filters
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['teacher', 'schedule.group.subjectModel']);

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by group
        if ($request->filled('group_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('group_id', $request->group_id);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('registered_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('registered_at', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('registered_at', 'desc')->paginate(20);

        // Load data for filters
        $teachers = User::whereIn('role', ['teacher', 'docente'])
            ->orderBy('name')
            ->get();
        
        $groups = Group::with('subjectModel')
            ->orderBy('subject')
            ->get();

        return view('admin.attendance.index', compact('attendances', 'teachers', 'groups'));
    }

    /**
     * Show the form for creating new attendance
     */
    public function create()
    {
        $teachers = User::whereIn('role', ['teacher', 'docente'])
            ->orderBy('name')
            ->get();
        
        return view('admin.attendance.create', compact('teachers'));
    }

    /**
     * Store a newly created attendance
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:schedules,id',
            'status' => 'required|in:present,absent,late',
            'aula' => 'nullable|string|max:50',
            'registered_at' => 'nullable|date',
        ]);

        // Check if attendance already exists for this schedule and date
        $existingAttendance = Attendance::where('teacher_id', $validated['teacher_id'])
            ->where('schedule_id', $validated['schedule_id'])
            ->whereDate('registered_at', $validated['registered_at'] ?? now())
            ->first();

        if ($existingAttendance) {
            return redirect()->back()
                ->with('error', 'Ya existe un registro de asistencia para este horario y fecha.');
        }

        // Get schedule to get group_id
        $schedule = Schedule::findOrFail($validated['schedule_id']);
        
        Attendance::create([
            'teacher_id' => $validated['teacher_id'],
            'schedule_id' => $validated['schedule_id'],
            'group_id' => $schedule->group_id,
            'status' => $validated['status'],
            'aula' => $validated['aula'],
            'registered_at' => $validated['registered_at'] ?? now(),
        ]);

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Asistencia registrada exitosamente.');
    }

    /**
     * Display the specified attendance
     */
    public function show(Attendance $attendance)
    {
        $attendance->load(['teacher', 'schedule.group.subjectModel']);
        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified attendance
     */
    public function edit(Attendance $attendance)
    {
        $attendance->load(['teacher', 'schedule.group']);
        
        return view('admin.attendance.edit', compact('attendance'));
    }

    /**
     * Update the specified attendance
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,late',
            'aula' => 'nullable|string|max:50',
            'registered_at' => 'nullable|date',
        ]);

        $attendance->update($validated);

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Asistencia actualizada exitosamente.');
    }

    /**
     * Remove the specified attendance
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Asistencia eliminada exitosamente.');
    }

    /**
     * Get schedules for a specific teacher (AJAX)
     */
    public function getTeacherSchedules(Request $request)
    {
        $teacherId = $request->input('teacher_id');
        
        $schedules = Schedule::with(['group.subjectModel'])
            ->whereHas('group', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'label' => $schedule->group->subjectModel 
                        ? "{$schedule->group->subjectModel->name} - {$schedule->group->group_name} - {$schedule->day} {$schedule->start_time}-{$schedule->end_time}"
                        : "{$schedule->group->subject} - {$schedule->group->group_name} - {$schedule->day} {$schedule->start_time}-{$schedule->end_time}",
                    'day' => $schedule->day,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'aula' => $schedule->aula,
                ];
            });

        return response()->json($schedules);
    }
}
