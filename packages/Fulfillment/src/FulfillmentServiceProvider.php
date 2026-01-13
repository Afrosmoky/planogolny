<?php

namespace Planogolny\Fulfillment;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Planogolny\Orders\Events\OrderPaid;
use Planogolny\Fulfillment\Listeners\FulfillPaidOrderListener;

final class FulfillmentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        logger('FulfillmentServiceProvider booted');
        Event::listen(
            OrderPaid::class,
            FulfillPaidOrderListener::class
        );
    }
}
