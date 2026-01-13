<?php

namespace Planogolny\Analysis\DTO;
final readonly class AnalysisEngineResultDTO
{
    public function __construct(
        public int $totalScore,
        public array $breakdown,
        public array $warnings,
        public float $dataCompletenessPercent,
        public string $confidenceLevel
    ) {}
}
