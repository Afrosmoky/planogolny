<?php

namespace Planogolny\Analysis\Mappers;

use Planogolny\Analysis\DTO\AnalysisEngineResultDTO;
use Planogolny\Analysis\DTO\AnalysisResultDTO;
use Planogolny\Analysis\DTO\CategoryScoreDTO;

final class AnalysisResultMapper
{
    public static function map(
        AnalysisEngineResultDTO $engineResult
    ): AnalysisResultDTO {
        $categories = [];

        foreach ($engineResult->breakdown as $category => $score) {
            $categories[] = new CategoryScoreDTO(
                category: (string) $category,
                score: (int) $score
            );
        }

        return new AnalysisResultDTO(
            categories: $categories,
            raw: [
                'total_score' => $engineResult->totalScore,
                'breakdown' => $engineResult->breakdown,
                'warnings' => $engineResult->warnings,
                'confidence' => $engineResult->confidenceLevel,
                'data_completeness_percent' => $engineResult->dataCompletenessPercent,
            ]
        );
    }
}
