<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Group;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance page with today's classes (CU5)
     */
    public function index()
    {
        $today = strtolower(Carbon::now()->format('l')); // 'monday', 'tuesday', etc.
        $currentHour = Carbon::now()->hour;

        // Get today's schedules for the authenticated teacher
        $todaySchedules = Schedule::whereHas('group', function ($query) {
                $query->where('teacher_id', auth()->id());
            })
            ->where('day', $today)
            ->with('group')
            ->orderBy('time_block')
            ->get();

        // Get attendance history
        $attendances = Attendance::where('teacher_id', auth()->id())
            ->with('schedule.group')
            ->orderBy('attended_at', 'desc')
            ->take(10)
            ->get();

        // Stats
        $stats = [
            'classes_today' => $todaySchedules->count(),
            'attended_today' => Attendance::where('teacher_id', auth()->id())
                ->whereDate('attended_at', Carbon::today())
                ->where('status', 'present')
                ->count(),
            'attended_month' => Attendance::where('teacher_id', auth()->id())
                ->whereMonth('attended_at', Carbon::now()->month)
                ->where('status', 'present')
                ->count(),
        ];

        return view('teacher.attendance', compact('todaySchedules', 'attendances', 'stats'));
    }

    /**
     * Store teacher attendance (CU5)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'status' => 'required|in:present,late,absent',
            'notes' => 'nullable|string|max:500',
        ]);

        // Verify the schedule belongs to a group of this teacher
        $schedule = Schedule::whereHas('group', function ($query) {
                $query->where('teacher_id', auth()->id());
            })
            ->findOrFail($validated['schedule_id']);

        // Check if attendance already registered today
        $existingAttendance = Attendance::where('schedule_id', $schedule->id)
            ->where('teacher_id', auth()->id())
            ->whereDate('attended_at', Carbon::today())
            ->first();

        if ($existingAttendance) {
            return back()->withErrors(['error' => 'Ya registraste tu asistencia para esta clase hoy.']);
        }

        Attendance::create([
            'schedule_id' => $schedule->id,
            'teacher_id' => auth()->id(),
            'attended_at' => Carbon::now(),
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('teacher.attendance')->with('success', '¡Asistencia registrada exitosamente!');
    }
}

