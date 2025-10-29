<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Subject;

class GroupController extends Controller
{
    /**
     * Show the form for creating a new group (CU4)
     */
    public function create()
    {
        $subjects = Subject::active()->orderBy('name')->get();
        return view('teacher.groups-create', compact('subjects'));
    }

    /**
     * Store a newly created group with schedules (CU4)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            // DB uses `name` as the canonical group identifier; validate uniqueness there
            'code' => 'required|string|max:50|unique:groups,name',
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
        $subject = Subject::findOrFail($validated['subject_id']);
        
        // Map form fields to the actual DB columns. The groups table stores the
        // canonical identifier in `name` and capacity in `capacity`.
        $group = Group::create([
            'name' => $validated['code'],
            'subject' => $subject->name, // Guardar nombre para compatibilidad
            'subject_id' => $validated['subject_id'],
            'capacity' => $validated['max_students'],
            'teacher_id' => auth()->id(),
        ]);
        // Ensure 'name' column is set (DB requires it)
        if (!$group->name) {
            $group->name = $validated['code'];
            $group->save();
        }

        // Create schedules
        foreach ($validated['schedules'] as $schedule) {
            // Map the teacher-facing schedule shape to DB columns.
            // Use `day_of_week` and compute start/end times from the integer time_block.
            $startHour = (int) $schedule['time_block'];
            $endHour = $startHour + 2; // classes are 2 hours long by convention

            Schedule::create([
                'group_id' => $group->id,
                'day_of_week' => $schedule['day'],
                'start_time' => sprintf('%02d:00:00', $startHour),
                'end_time' => sprintf('%02d:00:00', $endHour),
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

