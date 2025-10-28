<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    /**
     * Display all schedules for admin "Ver Horarios" view
     */
    public function viewAll()
    {
        $groups = Group::with(['teacher', 'schedules'])
            ->orderBy('subject')
            ->get();

        return view('schedules', compact('groups'));
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
            'code' => 'required|string|max:50|unique:groups,code,' . $groupId,
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
        
        // Update group info
        $group->update([
            'subject' => $validated['subject'],
            'code' => $validated['code'],
            'max_students' => $validated['max_students'],
            'classroom' => $validated['classroom'],
        ]);

        // Delete old schedules and create new ones
        $group->schedules()->delete();
        
        foreach ($validated['schedules'] as $schedule) {
            Schedule::create([
                'group_id' => $group->id,
                'day' => $schedule['day'],
                'time_block' => $schedule['time_block'],
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

