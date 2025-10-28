<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $user = $this->userFromRequest($request);
        if (! $user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        if ($user->role !== 'docente') {
            return response()->json(['message' => 'Permiso denegado'], 403);
        }

        $data = $request->validate([
            'group_id' => 'required|integer|exists:groups,id',
            'schedule_id' => 'required|integer|exists:schedules,id',
            'status' => 'nullable|in:present,absent,late',
            'aula' => 'nullable|string',
        ]);

        // optional: verify schedule belongs to group and group belongs to teacher
        $group = Group::find($data['group_id']);
        if ($group->teacher_id !== $user->id) {
            return response()->json(['message' => 'El docente no está asignado a ese grupo'], 403);
        }

        $schedule = Schedule::find($data['schedule_id']);
        if ($schedule->group_id !== $group->id) {
            return response()->json(['message' => 'El horario no pertenece al grupo indicado'], 422);
        }

        $attendance = Attendance::create([
            'teacher_id' => $user->id,
            'group_id' => $group->id,
            'schedule_id' => $schedule->id,
            'status' => $data['status'] ?? 'present',
            'aula' => $data['aula'] ?? $schedule->aula,
        ]);

        return response()->json(['attendance' => $attendance], 201);
    }

    private function userFromRequest(Request $request): ?User
    {
        $header = $request->header('Authorization');
        if (! $header) return null;
        if (! str_starts_with($header, 'Bearer ')) return null;
        $token = trim(substr($header, 7));
        if (! $token) return null;

        $hashed = hash('sha256', $token);
        return User::where('api_token', $hashed)->first();
    }
}
