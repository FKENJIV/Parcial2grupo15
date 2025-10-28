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
    Route::view('administrar-carga', 'admin-load')->name('administrar.carga');
    Route::view('registro-asistencia', 'attendance')->name('registro.asistencia');

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
