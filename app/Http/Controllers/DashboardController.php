<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total teachers in the system (both 'teacher' and 'docente' roles)
        $totalTeachers = User::whereIn('role', ['teacher', 'docente'])->count();
        
        // Total active groups
        $activeGroups = Group::count();
        
        // Today's attendance records (using created_at as the attendance date)
        $todayAttendance = Attendance::whereDate('created_at', Carbon::today())
            ->where('status', 'present')
            ->count();
        
        // Pending tasks (placeholder - can be customized based on business logic)
        // For now, show count of groups without attendance today
        $todayDay = Carbon::now()->format('D'); // 'Mon', 'Tue', 'Wed', etc.
        $scheduledToday = \App\Models\Schedule::where('day_of_week', $todayDay)->count();
        $pendingTasks = max(0, $scheduledToday - $todayAttendance);
        
        return view('dashboard', [
            'totalTeachers' => $totalTeachers,
            'activeGroups' => $activeGroups,
            'todayAttendance' => $todayAttendance,
            'pendingTasks' => $pendingTasks,
        ]);
    }
}
