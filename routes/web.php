<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\PasswordController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;
use Laravel\Fortify\Http\Controllers\UpdatePasswordController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Livewire\Volt\Volt;

Route::get('/', function () {
    // Redirect authenticated users to dashboard, guests to the login page
    if (auth()->check()) {
        // Redirect based on role
        if (in_array(auth()->user()->role, ['teacher', 'docente'])) {
            return redirect()->route('teacher.dashboard');
        }
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

// Admin/Superuser dashboard
Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// Teacher dashboard
Route::get('teacher/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'teacher'])
    ->name('teacher.dashboard');

// Application pages used in the UI (protected by auth)
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('horarios', [\App\Http\Controllers\Admin\ScheduleController::class, 'viewAll'])->name('horarios');

    // Teacher management routes (admin only)
    Route::resource('docentes', TeacherController::class)->names([
        'index' => 'docentes.index',
        'create' => 'docentes.create',
        'store' => 'docentes.store',
        'show' => 'docentes.show',
        'edit' => 'docentes.edit',
        'update' => 'docentes.update',
        'destroy' => 'docentes.destroy',
    ]);

    // Schedule management routes (admin only)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedules.index');
        Route::get('schedules/{group}/edit', [\App\Http\Controllers\Admin\ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('schedules/{group}', [\App\Http\Controllers\Admin\ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('schedules/{group}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('schedules.destroy');
        
        // Subject management routes
        Route::get('subjects', [\App\Http\Controllers\Admin\SubjectController::class, 'index'])->name('subjects.index');
        Route::post('subjects', [\App\Http\Controllers\Admin\SubjectController::class, 'store'])->name('subjects.store');
    // Quick toggle for active/inactive
    Route::post('subjects/{subject}/toggle-active', [\App\Http\Controllers\Admin\SubjectController::class, 'toggleActive'])->name('subjects.toggle-active');
        Route::put('subjects/{subject}', [\App\Http\Controllers\Admin\SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('subjects/{subject}', [\App\Http\Controllers\Admin\SubjectController::class, 'destroy'])->name('subjects.destroy');

        // Attendance management routes (admin only)
    Route::get('attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/create', [\App\Http\Controllers\Admin\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('attendance.store');
    // AJAX helper to fetch schedules for a teacher must be declared before the
    // parameterized attendance routes so it doesn't get captured as {attendance}.
    Route::get('attendance/teacher-schedules', [\App\Http\Controllers\Admin\AttendanceController::class, 'getTeacherSchedules'])->name('attendance.teacher-schedules');
    Route::get('attendance/{attendance}', [\App\Http\Controllers\Admin\AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('attendance/{attendance}/edit', [\App\Http\Controllers\Admin\AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('attendance/{attendance}', [\App\Http\Controllers\Admin\AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('attendance/{attendance}', [\App\Http\Controllers\Admin\AttendanceController::class, 'destroy'])->name('attendance.destroy');

        // Group management routes (admin only)
        Route::get('groups', [\App\Http\Controllers\Admin\GroupController::class, 'index'])->name('groups.index');
        Route::get('groups/create', [\App\Http\Controllers\Admin\GroupController::class, 'create'])->name('groups.create');
        Route::post('groups', [\App\Http\Controllers\Admin\GroupController::class, 'store'])->name('groups.store');
        Route::get('groups/{group}/edit', [\App\Http\Controllers\Admin\GroupController::class, 'edit'])->name('groups.edit');
        Route::put('groups/{group}', [\App\Http\Controllers\Admin\GroupController::class, 'update'])->name('groups.update');
        Route::delete('groups/{group}', [\App\Http\Controllers\Admin\GroupController::class, 'destroy'])->name('groups.destroy');
    });
});

// Teacher-specific routes (CU3, CU4, CU5)
Route::middleware(['auth', 'verified', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // CU3: Ver horarios asignados
    Route::get('schedules', [\App\Http\Controllers\Teacher\ScheduleController::class, 'index'])->name('schedules');

    // CU4: Crear grupo y asignar horario
    Route::get('groups/create', [\App\Http\Controllers\Teacher\GroupController::class, 'create'])->name('groups.create');
    Route::post('groups', [\App\Http\Controllers\Teacher\GroupController::class, 'store'])->name('groups.store');

    // CU5: Registrar asistencia docente
    Route::get('attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance');
    Route::post('attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('attendance.store');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
