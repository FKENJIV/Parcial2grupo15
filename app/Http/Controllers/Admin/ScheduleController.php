<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Subject;

class ScheduleController extends Controller
{
    /**
     * Display all schedules for admin "Ver Horarios" view
     */
    public function viewAll(Request $request)
    {
        $query = Group::with(['teacher', 'schedules', 'subjectModel']);

        // Filtro por materia
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filtro por docente
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $groups = $query->orderBy('subject')->get();
        
        // Obtener materias y docentes para los filtros
        $subjects = Subject::active()->orderBy('name')->get();
        $teachers = User::whereIn('role', ['teacher', 'docente'])->orderBy('name')->get();

        return view('schedules', compact('groups', 'subjects', 'teachers'));
    }

    /**
     * Display all teachers and their schedules for management
     */
    public function index()
    {
        $teachers = User::whereIn('role', ['teacher', 'docente'])
            ->with(['groups.schedules'])
            ->get();

        return view('admin.schedules-manage', compact('teachers'));
    }

    /**
     * Show edit form for a specific group's schedules
     */
    public function edit($groupId)
    {
        $group = Group::with(['schedules', 'teacher'])->findOrFail($groupId);
        
        return view('admin.schedules-edit', compact('group'));
    }

    /**
     * Update schedules for a group
     */
    public function update(Request $request, $groupId)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            // use `name` as the unique DB column
            'code' => 'required|string|max:50|unique:groups,name,' . $groupId,
            'max_students' => 'required|integer|min:1|max:100',
            'classroom' => 'required|string|max:50',
            'schedules' => 'required|array|min:1|max:9',
            'schedules.*.day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'schedules.*.time_block' => 'required|integer|min:7|max:20',
        ]);

        // Validate hours per day (1-3 hours)
        $dayHours = [];
        foreach ($validated['schedules'] as $schedule) {
            $day = $schedule['day'];
            if (!isset($dayHours[$day])) {
                $dayHours[$day] = 0;
            }
            $dayHours[$day]++;
        }

        if (count($dayHours) > 3) {
            return back()->withErrors(['schedules' => 'Los horarios deben estar en un máximo de 3 días diferentes.'])->withInput();
        }

        foreach ($dayHours as $day => $hours) {
            if ($hours < 1 || $hours > 3) {
                return back()->withErrors(['schedules' => "Cada día debe tener entre 1 y 3 horas de clase."])->withInput();
            }
        }

        $group = Group::findOrFail($groupId);
        
        // Update group info (map to actual DB columns)
        $group->update([
            'subject' => $validated['subject'],
            'name' => $validated['code'],
            'capacity' => $validated['max_students'],
        ]);
        // classroom may not be a DB column; if present, set attribute and save
        if (array_key_exists('classroom', $validated)) {
            $group->classroom = $validated['classroom'];
            $group->save();
        }

        // Delete old schedules and create new ones
        $group->schedules()->delete();
        
        foreach ($validated['schedules'] as $schedule) {
            $startHour = (int) $schedule['time_block'];
            $endHour = $startHour + 2; // 2-hour classes by convention

            Schedule::create([
                'group_id' => $group->id,
                'day_of_week' => $schedule['day'],
                'start_time' => sprintf('%02d:00:00', $startHour),
                'end_time' => sprintf('%02d:00:00', $endHour),
            ]);
        }

        return redirect()->route('admin.schedules.index')->with('success', 'Horarios actualizados exitosamente.');
    }

    /**
     * Delete a group and its schedules
     */
    public function destroy($groupId)
    {
        $group = Group::findOrFail($groupId);
        $group->schedules()->delete();
        $group->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Grupo eliminado exitosamente.');
    }
}

