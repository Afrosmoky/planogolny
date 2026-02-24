<?php

declare(strict_types=1);

namespace Planogolny\Analysis\Actions;

use App\Models\Analysis;

final readonly class MarkAnalysisAsReadyAction
{
    public function execute(Analysis $analysis): void
    {
        $analysis->update([
            'status' => 'ready',
        ]);
    }
}
