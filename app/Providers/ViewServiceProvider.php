<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Tetapan;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share system settings with all views
        View::composer('*', function ($view) {
            try {
                $systemName = Tetapan::getSystemName();
                $systemVersion = Tetapan::getSystemVersion();
                $view->with([
                    'systemName' => $systemName,
                    'systemVersion' => $systemVersion,
                    'pageTitle' => $systemName . ' - Sistem Pengurusan Jenazah'
                ]);
            } catch (\Exception $e) {
                // Fallback if database is not available
                $view->with([
                    'systemName' => 'E-Kubur',
                    'systemVersion' => '1.0.0',
                    'pageTitle' => 'E-Kubur - Sistem Pengurusan Jenazah'
                ]);
            }
        });
    }
}
