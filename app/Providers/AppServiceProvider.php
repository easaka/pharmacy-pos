<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\InventoryAlertService;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(\App\Services\NotificationSetting::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function ($view) {
            $alerts = app(InventoryAlertService::class)->generateAlerts();
            $view->with('alerts', $alerts);
        });
    }
}
