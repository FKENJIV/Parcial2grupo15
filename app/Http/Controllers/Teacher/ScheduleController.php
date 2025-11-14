<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    /**
     * Display schedules for the authenticated teacher (CU3)
     */
    public function index()
    {
        $groups = Group::with('schedules')
            ->where('teacher_id', auth()->id())
            ->get();

        // Organize schedules by day and time for the grid
        $scheduleGrid = [];
        $stats = [
            'total_groups' => $groups->count(),
            'total_hours' => 0,
            'total_students' => $groups->sum('max_students'),
        ];

        foreach ($groups as $group) {
            foreach ($group->schedules as $schedule) {
                $day = $schedule->day;
                $time = $schedule->time_block;
                
                if (!isset($scheduleGrid[$day])) {
                    $scheduleGrid[$day] = [];
                }
                
                $scheduleGrid[$day][$time] = [
                    'subject' => $group->subject,
                    'code' => $group->code,
                    'classroom' => $schedule->aula ?? 'N/A', // Usar el aula del schedule
                    'group_id' => $group->id,
                ];
                
                $stats['total_hours']++;
            }
        }

        return view('teacher.schedules', compact('groups', 'scheduleGrid', 'stats'));
    }
}

