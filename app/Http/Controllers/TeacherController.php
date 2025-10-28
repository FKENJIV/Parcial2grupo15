<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'teacher');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Pagination
        $teachers = $query->paginate(10);

        return view('teachers', compact('teachers'));
    }

    public function show(User $teacher)
    {
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
        ]);

        $validated['password'] = Hash::make('password123'); // Default password
        $validated['role'] = 'teacher';

        User::create($validated);

        return redirect()->route('docentes.index')->with('success', 'Docente creado exitosamente.');
    }

    public function update(Request $request, User $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($teacher->id)],
            'code' => ['required', 'string', Rule::unique('users')->ignore($teacher->id)],
            'type' => 'required|in:titular,invitado,auxiliar',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'specialties' => 'nullable|string|max:500',
        ]);

        $teacher->update($validated);

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