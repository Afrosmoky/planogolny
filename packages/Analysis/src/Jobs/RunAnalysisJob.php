<?php

declare(strict_types=1);

namespace Planogolny\Analysis\Jobs;

use App\Models\Analysis;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Planogolny\Analysis\Actions\CreateAnalysisResultAction;
use Planogolny\Analysis\Actions\MarkAnalysisAsReadyAction;
use Planogolny\Analysis\Actions\PrepareAnalysisInputAction;
use Planogolny\Analysis\DTO\ResultStoreDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

class RunAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $analysisId
    ) {}

    public function handle(
        CreateAnalysisResultAction $createResult,
        MarkAnalysisAsReadyAction $markReady,
        PrepareAnalysisInputAction $prepare,
    ): void {
        $analysis = Analysis::findOrFail($this->analysisId);

        $analysis->update(['status' => 'processing']);

        info("Analiza rozpoczęta. Status analizy: {$analysis->status}");

        $input = $prepare->execute($analysis);

        $dto = new ResultStoreDTO(
            legalConstraints: $input->legalConstraints,
            gusData: ' ',
            surroundings: $input->surroundings,
            meta: [
                'ready_for_report' => true,
                'missing' => [],
            ]
        );

        $createResult->execute($analysis, $dto);
        info("Wynik zapisany.");

        $markReady->execute($analysis);
        info("Status analizy zmieniony na: {$analysis->status}");
    }
}
