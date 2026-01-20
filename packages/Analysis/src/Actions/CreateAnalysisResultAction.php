<?php

namespace Planogolny\Analysis\Actions;
use App\Models\Analysis;
use App\Models\AnalysisResult;
use Planogolny\Analysis\DTO\AnalysisResultDTO;
use Planogolny\Analysis\DTO\ResultStoreDTO;

final readonly class CreateAnalysisResultAction
{
    public function __construct(
        public AnalysisResult $analysisResult,
    ){}

    public function execute(
        Analysis $analysis,
        ResultStoreDTO $dto
    ): AnalysisResult {
        return AnalysisResult::create([
            'analyses_id'    => $analysis->id,
            'surroundings'       => $dto->surroundings->toArray(),
            'legal_constraints'      => $dto->legalConstraints->toArray(),
            'gus_data'       => $dto->gusData,
            'meta'           => $dto->meta,
            'generated_at'   => now(),
        ]);
    }
}
