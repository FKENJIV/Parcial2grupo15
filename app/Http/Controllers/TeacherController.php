<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['teacher', 'docente'])->with('subjects');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Subject/Specialty filter
        if ($request->filled('subject_id')) {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject_id);
            });
        }

        // Pagination
        $teachers = $query->paginate(10);

        // Load subjects for filters
        $subjects = Subject::active()->orderBy('name')->get();

        return view('teachers', compact('teachers', 'subjects'));
    }

    public function show($id)
    {
        // Resolve by id explicitly to avoid implicit binding issues with route parameter names
        $teacher = User::with('subjects')->findOrFail($id);

        // Log the teacher payload for debugging (can be removed later)
        \Log::info('TeacherController@show payload', $teacher->toArray());

        return response()->json($teacher);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'code' => 'required|string|unique:users,code',
            'type' => 'required|in:titular,invitado,auxiliar',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'specialties' => 'nullable|string|max:500',
            'password' => 'required|string|min:8|confirmed',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'new_subject_name' => 'nullable|string|max:255',
            'new_subject_code' => 'nullable|string|max:20|unique:subjects,code',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'teacher';

        $teacher = User::create($validated);

        // Handle new subject creation if provided
        if (!empty($validated['new_subject_name']) && !empty($validated['new_subject_code'])) {
            $newSubject = Subject::create([
                'name' => $validated['new_subject_name'],
                'code' => $validated['new_subject_code'],
                'active' => true,
                'credits' => 4, // Default
            ]);
            $validated['subject_ids'][] = $newSubject->id;
        }

        // Sync subjects (specialties)
        if (!empty($validated['subject_ids'])) {
            $teacher->subjects()->sync($validated['subject_ids']);
        }

        return redirect()->route('docentes.index')->with('success', 'Docente creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $teacher = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($teacher->id)],
            'code' => ['required', 'string', Rule::unique('users')->ignore($teacher->id)],
            'type' => 'required|in:titular,invitado,auxiliar',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'specialties' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'new_subject_name' => 'nullable|string|max:255',
            'new_subject_code' => 'nullable|string|max:20|unique:subjects,code',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $teacher->update($validated);

        // Handle new subject creation if provided
        if (!empty($validated['new_subject_name']) && !empty($validated['new_subject_code'])) {
            $newSubject = Subject::create([
                'name' => $validated['new_subject_name'],
                'code' => $validated['new_subject_code'],
                'active' => true,
                'credits' => 4, // Default
            ]);
            $validated['subject_ids'][] = $newSubject->id;
        }

        // Sync subjects (specialties)
        $teacher->subjects()->sync($validated['subject_ids'] ?? []);

        return redirect()->route('docentes.index')->with('success', 'Docente actualizado exitosamente.');
    }

    public function destroy(User $teacher)
    {
        // Prevent deletion of super admin or if teacher has active groups
        if ($teacher->role === 'admin') {
            return redirect()->route('docentes.index')->with('error', 'No se puede eliminar al administrador.');
        }

        // Check if teacher has active groups (this would need a relationship)
        // if ($teacher->groups()->where('status', 'active')->exists()) {
        //     return redirect()->route('docentes.index')->with('error', 'No se puede eliminar un docente con grupos activos.');
        // }

        $teacher->delete();

        return redirect()->route('docentes.index')->with('success', 'Docente eliminado exitosamente.');
    }
}