<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    // Create group and assign schedules. Enforce: each block <= 3 hours, distinct days <= 3
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
            'name' => 'required|string',
            'subject' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'schedules' => 'required|array|min:1',
            'schedules.*.day_of_week' => 'required|string',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.aula' => 'nullable|string',
        ]);

        $schedules = $data['schedules'];

    // validate rules: each block duration <= 3 hours, distinct days <= 3
    $days = [];
        foreach ($schedules as $s) {
            $start = Carbon::createFromFormat('H:i', $s['start_time']);
            $end = Carbon::createFromFormat('H:i', $s['end_time']);
            // compute minutes using timestamps to avoid any Carbon sign/absolute issues
            $diffSeconds = $end->getTimestamp() - $start->getTimestamp();
            $diffMinutes = intdiv($diffSeconds, 60);
            // debug log to help tests understand durations
            \Illuminate\Support\Facades\Log::debug('Schedule duration check', ['start' => $s['start_time'], 'end' => $s['end_time'], 'start_dt' => $start->toDateTimeString(), 'end_dt' => $end->toDateTimeString(), 'minutes' => $diffMinutes, 'diffSeconds' => $diffSeconds]);
            if ($diffMinutes > 180) {
                return response()->json(['message' => 'Cada bloque no puede exceder 3 horas'], 422);
            }
            $days[$s['day_of_week']] = true;
            // check overlap with existing schedules for this teacher
            $conflict = Schedule::whereHas('group', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->where('day_of_week', $s['day_of_week'])
            ->where(function ($q) use ($s) {
                // overlap if existing.start < new.end AND existing.end > new.start
                $q->where('start_time', '<', $s['end_time'])
                  ->where('end_time', '>', $s['start_time']);
            })->exists();

            if ($conflict) {
                return response()->json(['message' => 'Conflicto de horario con otro grupo asignado al docente'], 422);
            }

            // check aula occupancy (no two groups can be in the same aula at overlapping times)
            if (! empty($s['aula'])) {
                $aulaConflict = Schedule::where('day_of_week', $s['day_of_week'])
                    ->where('aula', $s['aula'])
                    ->where(function ($q) use ($s) {
                        $q->where('start_time', '<', $s['end_time'])
                          ->where('end_time', '>', $s['start_time']);
                    })->exists();

                if ($aulaConflict) {
                    return response()->json(['message' => 'Aula ocupada en ese horario'], 422);
                }
            }

            // check overlap with schedules assigned to other docentes (prevent different teachers being scheduled at the same time)
            $otherTeacherConflict = Schedule::whereHas('group', function ($q) use ($user) {
                $q->where('teacher_id', '!=', $user->id);
            })
            ->where('day_of_week', $s['day_of_week'])
            ->where(function ($q) use ($s) {
                $q->where('start_time', '<', $s['end_time'])
                  ->where('end_time', '>', $s['start_time']);
            })->exists();

            if ($otherTeacherConflict) {
                return response()->json(['message' => 'Conflicto de horario con otro docente'], 422);
            }
        }

        if (count($days) > 3) {
            return response()->json(['message' => 'No se pueden asignar a más de 3 días por grupo'], 422);
        }

        // create group
        $group = Group::create([
            'name' => $data['name'],
            'subject' => $data['subject'] ?? null,
            'capacity' => $data['capacity'] ?? 30,
            'teacher_id' => $user->id,
        ]);

        foreach ($schedules as $s) {
            Schedule::create([
                'group_id' => $group->id,
                'day_of_week' => $s['day_of_week'],
                'start_time' => $s['start_time'],
                'end_time' => $s['end_time'],
                'aula' => $s['aula'] ?? null,
            ]);
        }

        return response()->json(['group' => $group->load('schedules')], 201);
    }

    private function userFromRequest(Request $request): ?User
    {
        $header = $request->header('Authorization');
        if (! $header) return null;
        if (! str_starts_with($header, 'Bearer ')) return null;
        $token = trim(substr($header, 7));
        if (! $token) return null;

        $hashed = hash('sha256', $token);
        $user = User::where('api_token', $hashed)->first();
        if (! $user) return null;
        // check token expiry when present
        if ($user->api_token_expires_at && \Illuminate\Support\Carbon::now()->greaterThan($user->api_token_expires_at)) {
            return null;
        }
        return $user;
    }
}
