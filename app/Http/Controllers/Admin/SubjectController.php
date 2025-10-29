<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects
     */
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Toggle active state (AJAX quick toggle)
     */
    public function toggleActive(Request $request, $id)
    {
        // Use a DB-level boolean expression to avoid Postgres type-mismatch
        // errors when the runtime might try to write 0/1 integers.
        DB::table('subjects')->where('id', $id)->update([
            'active' => DB::raw('NOT active'),
            'updated_at' => now(),
        ]);

        $subject = Subject::findOrFail($id);

        return response()->json([
            'success' => true,
            'active' => (bool) $subject->active,
            'message' => $subject->active ? 'Materia activada.' : 'Materia desactivada.'
        ]);
    }

    /**
     * Store a newly created subject
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'description' => 'nullable|string|max:500',
            'credits' => 'required|integer|min:1|max:12',
            'active' => 'boolean',
        ]);

    // PostgreSQL is strict with boolean types; use raw boolean literal
    $validated['active'] = $request->has('active') ? DB::raw('true') : DB::raw('false');

    Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Materia creada exitosamente.');
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
            'description' => 'nullable|string|max:500',
            'credits' => 'required|integer|min:1|max:12',
            'active' => 'boolean',
        ]);

    // Use DB::raw boolean literal to avoid PostgreSQL type mismatch
    $validated['active'] = $request->has('active') ? DB::raw('true') : DB::raw('false');

    $subject->update($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Materia actualizada exitosamente.');
    }

    /**
     * Remove the specified subject
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        
        // Check if subject has groups
        if ($subject->groups()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar esta materia porque tiene grupos asignados.']);
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Materia eliminada exitosamente.');
    }
}
