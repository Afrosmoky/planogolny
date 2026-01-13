<?php

namespace Planogolny\Analysis\Actions;

use Planogolny\Analysis\DTO\AnalysisInputDTO;
use Planogolny\Analysis\Services\ScoringService;
use Planogolny\Analysis\DTO\AnalysisResultDTO;
use Planogolny\Analysis\Exceptions\AnalysisException;
final readonly class RunAnalysisAction
{
    public function __construct(
        protected ScoringService $scoring
    ) {}

    public function execute(AnalysisInputDTO $input): AnalysisResultDTO
    {
        try {
            return $this->scoring->calculate($input);
        } catch (\Throwable $e) {
            throw new AnalysisException("Analysis failed: " . $e->getMessage(), 0, $e);
        }
    }
}
