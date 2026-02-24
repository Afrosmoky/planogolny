<?php

declare(strict_types=1);

namespace Planogolny\Analysis\Services;

use Planogolny\Analysis\DTO\AnalysisInputDTO;
use Planogolny\Analysis\DTO\AnalysisResultDTO;
use Planogolny\Analysis\DTO\CategoryScoreDTO;
final class ScoringService
{
    public function __construct(
        private ClassificationService $classificationService,
        private RestrictionService $restrictionService,
    ) {}

    public function calculate(AnalysisInputDTO $input): AnalysisResultDTO
    {
        $baseScores = $this->classificationService->classify($input->surroundings);

        $restrictionResult = $this->restrictionService->apply($input->surroundings);

        $finalScores = [];

        foreach ($baseScores as $category => $score) {
            $penalty = $restrictionResult['penalties'][$category] ?? 0;
            $finalScores[$category] = max(0, $score - $penalty);
        }

        $normalizedScores = $this->normalize($finalScores);

        $categories = [];

        foreach ($normalizedScores as $category => $score) {
            $categories[] = new CategoryScoreDTO(
                category: $category,
                score: $score
            );
        }

        return new AnalysisResultDTO(
            categories: $categories,
            raw: [
                'base_scores' => $baseScores,
                'penalties' => $restrictionResult['penalties'],
                'warnings' => $restrictionResult['warnings'],
            ]
        );
    }

    private function normalize(array $scores): array
    {
        $sum = array_sum($scores);

        if ($sum === 0) {
            return $scores;
        }

        $normalized = [];

        foreach ($scores as $category => $score) {
            $normalized[$category] = (int) round(($score / $sum) * 100);
        }

        return $normalized;
    }
}
