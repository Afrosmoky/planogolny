<?php

declare(strict_types=1);

namespace Planogolny\Orders;

use Illuminate\Support\ServiceProvider;

class OrdersServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadMigrationsFrom(dirname(__DIR__).'/database/migrations');
    }
}
