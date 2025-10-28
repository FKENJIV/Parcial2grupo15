<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = Str::random(60);
        $user->api_token = hash('sha256', $token);
    // token expires in 30 days (adjustable)
    $user->api_token_expires_at = Carbon::now()->addDays(30);
    $user->save();

        return response()->json([
            'token' => $token,
            'user' => $user->only(['id', 'name', 'email', 'role']),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $this->userFromRequest($request);
        if (! $user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

    $user->api_token = null;
    $user->api_token_expires_at = null;
    $user->save();

        return response()->json(['message' => 'Sesión finalizada']);
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
        if ($user->api_token_expires_at && Carbon::now()->greaterThan($user->api_token_expires_at)) {
            return null; // token expired
        }

        return $user;
    }
}
