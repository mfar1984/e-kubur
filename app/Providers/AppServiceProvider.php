<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Models\Tetapan;

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
    public function boot(): void
    {
        // Apply session lifetime from Tetapan (Masa Tamat Sesi - minit)
        try {
            $minutes = (int) (Tetapan::get('session_timeout', config('session.lifetime')));
            if ($minutes && $minutes > 0) {
                Config::set('session.lifetime', $minutes);
            }
        } catch (\Throwable $e) {
            // Fail silently if database not ready during install
        }
    }
}
