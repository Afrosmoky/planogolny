<?php

declare(strict_types=1);

namespace Planogolny\Reporting;

use Illuminate\Support\ServiceProvider;
use Planogolny\Reporting\Services\PdfRenderer;

class ReportingServiceProvider extends ServiceProvider
{
    public function register():void
    {
        $this->app->singleton(PdfRenderer::class, fn () => new PdfRenderer());
    }

    public function boot():void
    {
        logger('ReportingServiceProvider booted');
    }
}
