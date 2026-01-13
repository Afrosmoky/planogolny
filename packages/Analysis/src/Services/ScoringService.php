<?php

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
        // 1️Klasyfikacja zabudowy (punkty bazowe)
        $baseScores = $this->classificationService->classify($input->surroundings);
        // przykład: ['single_family' => 60, 'services' => 20, ...]

        // 2️Ograniczenia (kary + warnings)
        $restrictionResult = $this->restrictionService->apply($input->surroundings);
        // ['penalties' => [...], 'warnings' => [...]]

        // 3️Zastosowanie kar
        $finalScores = [];

        foreach ($baseScores as $category => $score) {
            $penalty = $restrictionResult['penalties'][$category] ?? 0;
            $finalScores[$category] = max(0, $score - $penalty);
        }

        // 4️⃣ Normalizacja do 100% (jeśli potrzeba)
        $normalizedScores = $this->normalize($finalScores);

        // 5️⃣ Mapowanie do DTO
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
