<?php

namespace App\Providers;

use App\Events\CompanyRegistered;
use App\Events\UserRegistered;
use App\Listener\SendWelcomeEmail;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Services\LogService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton('logservice', function ($app) {
            return new LogService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        Inertia::share('flash', session('flash')); // Flash üzenetek megosztása
        Event::listen(UserRegistered::class, SendWelcomeEmail::class);
        Event::listen(CompanyRegistered::class, SendWelcomeEmail::class);
    }
}
