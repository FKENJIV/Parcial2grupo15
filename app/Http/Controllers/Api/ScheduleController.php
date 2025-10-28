<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // Get assigned schedules for authenticated docente
    public function index(Request $request)
    {
        $user = $this->userFromRequest($request);
        if (! $user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        if ($user->role !== 'docente') {
            return response()->json(['message' => 'Permiso denegado'], 403);
        }

        $groups = Group::with('schedules')->where('teacher_id', $user->id)->get();

        return response()->json(['groups' => $groups]);
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
