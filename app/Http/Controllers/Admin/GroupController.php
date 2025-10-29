<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * Show the form for creating a new group
     */
    public function create()
    {
        $teachers = User::whereIn('role', ['teacher', 'docente'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        $subjects = Subject::active()
            ->orderBy('name')
            ->get();

        return view('admin.groups-create', compact('teachers', 'subjects'));
    }

    /**
     * Store a newly created group in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'group_name' => 'required|string|max:255',
            'schedules' => 'required|array|min:1',
            'schedules.*.day' => 'required|string|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.aula' => 'required|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Get subject to save its name for compatibility
            $subject = Subject::findOrFail($validated['subject_id']);

            // Create the group
            $group = Group::create([
                'teacher_id' => $validated['teacher_id'],
                'subject_id' => $validated['subject_id'],
                'subject' => $subject->name, // For backward compatibility
                'name' => $validated['group_name'], // DB expects 'name' column
            ]);

            // Create schedules
            foreach ($validated['schedules'] as $scheduleData) {
                Schedule::create([
                    'group_id' => $group->id,
                    // The schedules table stores `day_of_week`, `start_time` and `end_time`
                    'day_of_week' => $scheduleData['day'],
                    'start_time' => $scheduleData['start_time'],
                    'end_time' => $scheduleData['end_time'],
                    'aula' => $scheduleData['aula'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.groups.index')
                ->with('success', 'Grupo creado exitosamente y asignado al docente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el grupo: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of all groups
     */
    public function index(Request $request)
    {
        $query = Group::with(['teacher', 'subjectModel', 'schedules']);

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Search by group name (DB column is `name`)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $groups = $query->orderBy('created_at', 'desc')->paginate(15);

        // Load data for filters
        $teachers = User::whereIn('role', ['teacher', 'docente'])
            ->orderBy('name')
            ->get();
        
        $subjects = Subject::active()
            ->orderBy('name')
            ->get();

        return view('admin.groups.index', compact('groups', 'teachers', 'subjects'));
    }

    /**
     * Show the form for editing the specified group
     */
    public function edit(Group $group)
    {
        $group->load(['teacher', 'subjectModel', 'schedules']);
        
        $teachers = User::whereIn('role', ['teacher', 'docente'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        $subjects = Subject::active()
            ->orderBy('name')
            ->get();

        return view('admin.groups-edit', compact('group', 'teachers', 'subjects'));
    }

    /**
     * Update the specified group
     */
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'group_name' => 'required|string|max:255',
            'schedules' => 'required|array|min:1',
            'schedules.*.id' => 'nullable|exists:schedules,id',
            'schedules.*.day' => 'required|string|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.aula' => 'required|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Get subject to save its name for compatibility
            $subject = Subject::findOrFail($validated['subject_id']);

            // Update the group (store name in the canonical DB column)
            $group->update([
                'teacher_id' => $validated['teacher_id'],
                'subject_id' => $validated['subject_id'],
                'subject' => $subject->name,
                'name' => $validated['group_name'], // ensure 'name' column is set
            ]);

            // Delete existing schedules not in the update
            $scheduleIds = collect($validated['schedules'])->pluck('id')->filter();
            $group->schedules()->whereNotIn('id', $scheduleIds)->delete();

            // Update or create schedules
            foreach ($validated['schedules'] as $scheduleData) {
                if (!empty($scheduleData['id'])) {
                    Schedule::where('id', $scheduleData['id'])->update([
                        'day_of_week' => $scheduleData['day'],
                        'start_time' => $scheduleData['start_time'],
                        'end_time' => $scheduleData['end_time'],
                        'aula' => $scheduleData['aula'],
                    ]);
                } else {
                    Schedule::create([
                        'group_id' => $group->id,
                        'day_of_week' => $scheduleData['day'],
                        'start_time' => $scheduleData['start_time'],
                        'end_time' => $scheduleData['end_time'],
                        'aula' => $scheduleData['aula'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.groups.index')
                ->with('success', 'Grupo actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el grupo: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified group
     */
    public function destroy(Group $group)
    {
        try {
            $group->delete();
            return redirect()->route('admin.groups.index')
                ->with('success', 'Grupo eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el grupo: ' . $e->getMessage());
        }
    }
}
