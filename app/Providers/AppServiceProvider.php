<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        Paginator::useBootstrapFive();

        // Share $setting (termasuk logo) ke semua view
        View::composer('*', function ($view) {
            try {
                $view->with('globalSetting', Setting::first());
            } catch (\Exception $e) {
                $view->with('globalSetting', null);
            }
        });
    }
}
