<?php

namespace Planogolny\Analysis\Helpers;

class AnalysisNormalizer
{
    public static function normalize(array $scores): array
    {
        $sum = array_sum($scores);

        if ($sum === 0) {
            return $scores;
        }

        return array_map(fn($v) => round(($v / $sum) * 100, 2), $scores);
    }
}
