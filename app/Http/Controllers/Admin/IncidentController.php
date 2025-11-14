<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Incident::with(['reporter', 'assignee']);

        if ($request->filled('aula')) {
            $query->where('aula', 'like', '%' . $request->aula . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('incident_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('incident_date', '<=', $request->date_to);
        }

        $incidents = $query->orderBy('incident_date', 'desc')->paginate(20);

        return view('admin.incidents.index', compact('incidents'));
    }

    public function create()
    {
        // Usar vista simple sin componentes complejos
        return view('admin.incidents.create-simple');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aula' => 'required|string|max:50',
            'incident_date' => 'required|date',
            'type' => 'required|in:daño,mantenimiento,limpieza,otro',
            'description' => 'required|string',
        ]);

        $validated['reported_by'] = auth()->id();
        $validated['status'] = 'reportado';

        Incident::create($validated);

        return redirect()->route('admin.incidents.index')
            ->with('success', 'Incidente registrado exitosamente.');
    }

    public function show(Incident $incident)
    {
        $incident->load(['reporter', 'assignee']);
        return view('admin.incidents.show', compact('incident'));
    }

    public function edit(Incident $incident)
    {
        $users = User::where('role', 'admin')->orderBy('name')->get();
        return view('admin.incidents.edit', compact('incident', 'users'));
    }

    public function update(Request $request, Incident $incident)
    {
        $oldValues = $incident->toArray();

        $validated = $request->validate([
            'aula' => 'required|string|max:50',
            'incident_date' => 'required|date',
            'type' => 'required|in:daño,mantenimiento,limpieza,otro',
            'description' => 'required|string',
            'status' => 'required|in:reportado,en_proceso,resuelto',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'resuelto' && !$incident->resolved_at) {
            $validated['resolved_at'] = now();
        }

        $incident->update($validated);

        AuditLog::log('updated', $incident, $oldValues, $validated);

        return redirect()->route('admin.incidents.index')
            ->with('success', 'Incidente actualizado exitosamente.');
    }

    public function destroy(Incident $incident)
    {
        $oldValues = $incident->toArray();
        $incident->delete();

        AuditLog::log('deleted', $incident, $oldValues, null);

        return redirect()->route('admin.incidents.index')
            ->with('success', 'Incidente eliminado exitosamente.');
    }
}
