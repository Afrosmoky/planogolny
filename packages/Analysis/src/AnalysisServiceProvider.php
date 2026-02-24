<?php

declare(strict_types=1);

namespace Planogolny\Analysis;

use Illuminate\Support\ServiceProvider;
use Planogolny\Analysis\Actions\RunAnalysisAction;
use Planogolny\Analysis\Services\ClassificationService;
use Planogolny\Analysis\Services\RestrictionService;
use Planogolny\Analysis\Services\ScoringService;
class AnalysisServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ClassificationService::class);
        $this->app->singleton(RestrictionService::class);
        $this->app->singleton(ScoringService::class);

        $this->app->singleton(RunAnalysisAction::class);
    }

    public function boot(): void
    {
        //
    }
}
