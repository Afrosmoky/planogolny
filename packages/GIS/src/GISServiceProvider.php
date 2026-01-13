<?php

namespace Planogolny\GIS;

use Illuminate\Support\ServiceProvider;
use Planogolny\GIS\Services\GisFacade;
class GISServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(GisFacade::class, function () {
            return new GisFacade();
        });
    }

    public function boot()
    {
        // If GIS exposes internal routes someday:
        // $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
