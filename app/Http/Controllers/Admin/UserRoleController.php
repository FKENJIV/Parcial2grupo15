<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->orderBy('name')->paginate(20);
        
        return view('admin.user-roles.index', compact('users'));
    }
    
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,teacher,docente',
        ]);
        
        $user->update($validated);
        
        return redirect()->back()->with('success', 'Rol actualizado exitosamente.');
    }
}
