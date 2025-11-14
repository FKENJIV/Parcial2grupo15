<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ScheduleChangeRequest;
use App\Models\Schedule;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ScheduleChangeRequestController extends Controller
{
    public function index()
    {
        $requests = ScheduleChangeRequest::with(['schedule.group.subjectModel', 'reviewer'])
            ->where('teacher_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('teacher.schedule-change-requests.index', compact('requests'));
    }

    public function create()
    {
        $schedules = Schedule::with(['group.subjectModel'])
            ->whereHas('group', function ($query) {
                $query->where('teacher_id', auth()->id());
            })
            ->get();

        return view('teacher.schedule-change-requests.create', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'new_day_of_week' => 'required|string|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'new_start_time' => 'required|date_format:H:i',
            'new_end_time' => 'required|date_format:H:i|after:new_start_time',
            'new_aula' => 'nullable|string|max:50',
            'reason' => 'required|string|min:10',
        ]);

        // Verify the schedule belongs to the teacher
        $schedule = Schedule::with('group')->findOrFail($validated['schedule_id']);
        if ($schedule->group->teacher_id !== auth()->id()) {
            return redirect()->back()->with('error', 'No tienes permiso para solicitar cambios en este horario.');
        }

        $validated['teacher_id'] = auth()->id();
        $validated['status'] = 'pendiente';

        $changeRequest = ScheduleChangeRequest::create($validated);

        AuditLog::log('created', $changeRequest, null, $validated);

        return redirect()->route('teacher.schedule-change-requests.index')
            ->with('success', 'Solicitud de cambio de horario enviada exitosamente.');
    }

    public function show(ScheduleChangeRequest $scheduleChangeRequest)
    {
        // Verify ownership
        if ($scheduleChangeRequest->teacher_id !== auth()->id()) {
            abort(403);
        }

        $scheduleChangeRequest->load(['schedule.group.subjectModel', 'reviewer']);
        return view('teacher.schedule-change-requests.show', compact('scheduleChangeRequest'));
    }
}
