<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleChangeRequest;
use App\Models\Schedule;
use App\Models\ScheduleHistory;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleChangeRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ScheduleChangeRequest::with(['teacher', 'schedule.group.subjectModel', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pendiente');
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.schedule-change-requests.index', compact('requests'));
    }

    public function show(ScheduleChangeRequest $scheduleChangeRequest)
    {
        $scheduleChangeRequest->load(['teacher', 'schedule.group.subjectModel', 'reviewer']);
        return view('admin.schedule-change-requests.show', compact('scheduleChangeRequest'));
    }

    public function approve(Request $request, ScheduleChangeRequest $scheduleChangeRequest)
    {
        if ($scheduleChangeRequest->status !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        $validated = $request->validate([
            'admin_comments' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $schedule = $scheduleChangeRequest->schedule;
            $oldValues = $schedule->toArray();

            // Update schedule
            $schedule->update([
                'day_of_week' => $scheduleChangeRequest->new_day_of_week,
                'start_time' => $scheduleChangeRequest->new_start_time,
                'end_time' => $scheduleChangeRequest->new_end_time,
                'aula' => $scheduleChangeRequest->new_aula ?? $schedule->aula,
            ]);

            // Update request status
            $scheduleChangeRequest->update([
                'status' => 'aprobado',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'admin_comments' => $validated['admin_comments'] ?? null,
            ]);

            // Create history record
            ScheduleHistory::create([
                'schedule_id' => $schedule->id,
                'changed_by' => auth()->id(),
                'change_type' => 'updated',
                'old_values' => $oldValues,
                'new_values' => $schedule->toArray(),
                'reason' => $scheduleChangeRequest->reason,
                'change_request_id' => $scheduleChangeRequest->id,
            ]);

            // AuditLog deshabilitado temporalmente
            // AuditLog::log('approved_schedule_change', $scheduleChangeRequest);

            DB::commit();

            return redirect()->route('admin.schedule-change-requests.index')
                ->with('success', 'Solicitud aprobada y horario actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al aprobar la solicitud: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, ScheduleChangeRequest $scheduleChangeRequest)
    {
        if ($scheduleChangeRequest->status !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        $validated = $request->validate([
            'admin_comments' => 'required|string',
        ]);

        $scheduleChangeRequest->update([
            'status' => 'rechazado',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_comments' => $validated['admin_comments'],
        ]);

        // AuditLog deshabilitado temporalmente
        // AuditLog::log('rejected_schedule_change', $scheduleChangeRequest);

        return redirect()->route('admin.schedule-change-requests.index')
            ->with('success', 'Solicitud rechazada.');
    }
}
