<?php

namespace Planogolny\Invoicing;

use Illuminate\Support\ServiceProvider;
use Planogolny\Invoicing\Listeners\HandleInvoiceGenerated;
use Planogolny\Invoicing\Services\IngInvoiceApi;
use Planogolny\Orders\Events\OrderPaid;
use Illuminate\Support\Facades\Event;

class InvoicingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IngInvoiceApi::class, fn () => new IngInvoiceApi());
    }

    public function boot()
    {
        Event::listen(OrderPaid::class, HandleInvoiceGenerated::class);
    }
}
