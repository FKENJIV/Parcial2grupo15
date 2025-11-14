<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleHistory;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;

class ScheduleHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ScheduleHistory::with(['schedule.group.subjectModel', 'user', 'changeRequest']);

        if ($request->filled('teacher_id')) {
            $query->whereHas('schedule.group', function ($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }

        if ($request->filled('group_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('group_id', $request->group_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('change_type')) {
            $query->where('change_type', $request->change_type);
        }

        $histories = $query->orderBy('created_at', 'desc')->paginate(20);

        $teachers = User::whereIn('role', ['teacher', 'docente'])->orderBy('name')->get();
        $groups = Group::with('subjectModel')->orderBy('name')->get();

        return view('admin.schedule-histories.index', compact('histories', 'teachers', 'groups'));
    }

    public function show(ScheduleHistory $scheduleHistory)
    {
        $scheduleHistory->load(['schedule.group.subjectModel', 'user', 'changeRequest']);
        return view('admin.schedule-histories.show', compact('scheduleHistory'));
    }
}
