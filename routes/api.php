<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\TeacherController;

// Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Schedules (Docente)
Route::get('/horarios', [ScheduleController::class, 'index']);

// Groups (Docente)
Route::post('/grupos', [GroupController::class, 'store']);

// Attendance (Docente)
Route::post('/asistencia', [AttendanceController::class, 'store']);

// Teacher management (Superusuario)
Route::post('/docentes', [TeacherController::class, 'store']);
Route::put('/docentes/{id}', [TeacherController::class, 'update']);
Route::delete('/docentes/{id}', [TeacherController::class, 'destroy']);
