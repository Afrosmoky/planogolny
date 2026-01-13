<?php

namespace Planogolny\Reporting;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Planogolny\Orders\Events\OrderPaid;
use Planogolny\Reporting\Listeners\SendReportOnOrderPaid;
use Planogolny\Reporting\Services\PdfRenderer;

class ReportingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PdfRenderer::class, fn () => new PdfRenderer());
    }

    public function boot()
    {
        logger('ReportingServiceProvider booted');

//        Event::listen(
//            OrderPaid::class,
//            SendReportOnOrderPaid::class
//        );
    }
}
