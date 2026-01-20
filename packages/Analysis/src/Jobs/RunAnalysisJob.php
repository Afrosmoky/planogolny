<?php

namespace Planogolny\Analysis\Jobs;

use App\Models\Analysis;
use App\Enums\AnalysisStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Planogolny\Analysis\Actions\CreateAnalysisResultAction;
use Planogolny\Analysis\Actions\MarkAnalysisAsReadyAction;
use Planogolny\Analysis\Actions\PrepareAnalysisInputAction;
use Planogolny\Analysis\Actions\RunAnalysisAction;
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

        // 1️⃣ status → analyzing
        $analysis->update(['status' => 'processing']);
        info("Analiza rozpoczęta. Status analizy: {$analysis->status}");
        /*
         * 2️⃣ TU PÓŹNIEJ:
         * - OSM
         * - odległości
         * - GUS
         * - plan miejscowy
         */

        $input = $prepare->execute($analysis);
        var_dump('Input: ');
        var_dump($input);

        //TODO: Poniższe przenieść do raportu
        //$result = $run->execute($input);


        // NA RAZIE placeholder
        $dto = new ResultStoreDTO(
            legalConstraints: $input->legalConstraints,
            gusData: ' ',
            surroundings: $input->surroundings,
            meta: [
                'ready_for_report' => true,
                'missing' => [],
            ]
        );


        // 3️⃣ zapis wyniku
        $createResult->execute($analysis, $dto);
        info("Wynik zapisany.");
        // 4️⃣ status → ready
        $markReady->execute($analysis);
        info("Status analizy zmieniony na: {$analysis->status}");
    }
}
