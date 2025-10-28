<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $teacherId = auth()->id();
        
        // Total groups assigned to this teacher
        $totalGroups = Group::where('teacher_id', $teacherId)->count();
        
        // Get today's day name in English lowercase (monday, tuesday, etc.)
        $todayDay = strtolower(Carbon::now()->format('l'));
        
        // Get today's classes (schedules for this teacher's groups on current day)
        $todayClasses = Schedule::whereHas('group', function($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
        ->where('day', $todayDay)
        ->with(['group'])
        ->orderBy('time_block')
        ->get();
        
        // Calculate attendance rate for current month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Total scheduled classes this month for this teacher
        $totalClassesThisMonth = Attendance::where('teacher_id', $teacherId)
            ->whereMonth('attended_at', $currentMonth)
            ->whereYear('attended_at', $currentYear)
            ->count();
        
        // Classes attended (status = 'present')
        $attendedClassesThisMonth = Attendance::where('teacher_id', $teacherId)
            ->whereMonth('attended_at', $currentMonth)
            ->whereYear('attended_at', $currentYear)
            ->where('status', 'present')
            ->count();
        
        // Calculate percentage (avoid division by zero)
        $attendanceRate = $totalClassesThisMonth > 0 
            ? round(($attendedClassesThisMonth / $totalClassesThisMonth) * 100, 1)
            : 100;
        
        return view('teacher-dashboard', [
            'totalGroups' => $totalGroups,
            'todayClasses' => $todayClasses,
            'attendanceRate' => $attendanceRate,
        ]);
    }
}
