<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class RoomAvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $aula = $request->input('aula');
        $day = $request->input('day');
        
        $availability = [];
        
        if ($aula && $day) {
            // Obtener todos los horarios ocupados para esa aula y día
            $occupiedSchedules = Schedule::with(['group.subjectModel', 'group.teacher'])
                ->where('aula', $aula)
                ->where('day_of_week', $day)
                ->orderBy('start_time')
                ->get();
            
            $availability = $occupiedSchedules;
        }
        
        // Obtener lista de aulas únicas
        $aulas = Schedule::select('aula')
            ->distinct()
            ->orderBy('aula')
            ->pluck('aula');
        
        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        
        return view('admin.room-availability.index', compact('availability', 'aulas', 'days', 'aula', 'day'));
    }
}
