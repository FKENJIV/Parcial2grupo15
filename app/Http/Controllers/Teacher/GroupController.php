<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Schedule;

class GroupController extends Controller
{
    /**
     * Show the form for creating a new group (CU4)
     */
    public function create()
    {
        return view('teacher.groups-create');
    }

    /**
     * Store a newly created group with schedules (CU4)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:groups,code',
            'max_students' => 'required|integer|min:1|max:100',
            'classroom' => 'required|string|max:50',
            'schedules' => 'required|array|min:1|max:9', // Mínimo 1, máximo 9 (3 días × 3 horas)
            'schedules.*.day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'schedules.*.time_block' => 'required|integer|min:7|max:20',
        ]);

        // Get days and count hours per day
        $dayHours = [];
        foreach ($validated['schedules'] as $schedule) {
            $day = $schedule['day'];
            if (!isset($dayHours[$day])) {
                $dayHours[$day] = 0;
            }
            $dayHours[$day]++;
        }

        // Validate: maximum 3 different days
        if (count($dayHours) > 3) {
            return back()->withErrors(['schedules' => 'Los horarios deben estar en un máximo de 3 días diferentes.'])->withInput();
        }

        // Validate: 1-3 hours per day
        foreach ($dayHours as $day => $hours) {
            if ($hours < 1 || $hours > 3) {
                return back()->withErrors(['schedules' => "Cada día debe tener entre 1 y 3 horas de clase. El día '{$day}' tiene {$hours} horas."])->withInput();
            }
        }

        // Create group
        $group = Group::create([
            'subject' => $validated['subject'],
            'code' => $validated['code'],
            'max_students' => $validated['max_students'],
            'classroom' => $validated['classroom'],
            'teacher_id' => auth()->id(),
        ]);

        // Create schedules
        foreach ($validated['schedules'] as $schedule) {
            Schedule::create([
                'group_id' => $group->id,
                'day' => $schedule['day'],
                'time_block' => $schedule['time_block'],
            ]);
        }

        return redirect()->route('teacher.schedules')->with('success', '¡Grupo creado exitosamente!');
    }

    /**
     * Display groups for the authenticated teacher
     */
    public function index()
    {
        $groups = Group::with('schedules')
            ->where('teacher_id', auth()->id())
            ->get();

        return view('teacher.groups.index', compact('groups'));
    }
}

