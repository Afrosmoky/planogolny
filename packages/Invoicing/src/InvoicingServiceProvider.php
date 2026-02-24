<?php

declare(strict_types=1);

namespace Planogolny\Invoicing;

use Illuminate\Support\ServiceProvider;
use Planogolny\Invoicing\Services\IngInvoiceApi;

class InvoicingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IngInvoiceApi::class, fn () => new IngInvoiceApi());
    }

    public function boot()
    {

    }
}
