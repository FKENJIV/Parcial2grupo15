<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    // Create new teacher (Superusuario only)
    public function store(Request $request)
    {
        $me = $this->userFromRequest($request);
        if (! $me) return response()->json(['message' => 'No autenticado'], 401);
        if ($me->role !== 'superusuario') return response()->json(['message' => 'Permiso denegado'], 403);

        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|in:docente,superusuario',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'docente',
        ]);

        return response()->json(['user' => $user], 201);
    }

    public function update(Request $request, $id)
    {
        $me = $this->userFromRequest($request);
        if (! $me) return response()->json(['message' => 'No autenticado'], 401);
        if ($me->role !== 'superusuario') return response()->json(['message' => 'Permiso denegado'], 403);

        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'sometimes|in:docente,superusuario',
        ]);

        if (isset($data['name'])) $user->name = $data['name'];
        if (isset($data['email'])) $user->email = $data['email'];
        if (! empty($data['password'])) $user->password = Hash::make($data['password']);
        if (isset($data['role'])) $user->role = $data['role'];

        $user->save();

        return response()->json(['user' => $user]);
    }

    public function destroy(Request $request, $id)
    {
        $me = $this->userFromRequest($request);
        if (! $me) return response()->json(['message' => 'No autenticado'], 401);
        if ($me->role !== 'superusuario') return response()->json(['message' => 'Permiso denegado'], 403);

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Docente eliminado']);
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
        if ($user->api_token_expires_at && \Illuminate\Support\Carbon::now()->greaterThan($user->api_token_expires_at)) {
            return null;
        }
        return $user;
    }
}
