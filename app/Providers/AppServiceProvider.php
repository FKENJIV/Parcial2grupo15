<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }

        // Registrar observers para auditoría automática
        \App\Models\User::observe(\App\Observers\ModelAuditObserver::class);
        \App\Models\Group::observe(\App\Observers\ModelAuditObserver::class);
        \App\Models\Schedule::observe(\App\Observers\ModelAuditObserver::class);
        \App\Models\Attendance::observe(\App\Observers\ModelAuditObserver::class);
        \App\Models\Subject::observe(\App\Observers\ModelAuditObserver::class);
        \App\Models\Incident::observe(\App\Observers\ModelAuditObserver::class);
        \App\Models\ScheduleChangeRequest::observe(\App\Observers\ModelAuditObserver::class);
        \App\Models\ScheduleHistory::observe(\App\Observers\ModelAuditObserver::class);
    }
}
