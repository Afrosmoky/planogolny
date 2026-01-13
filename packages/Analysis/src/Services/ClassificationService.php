<?php

namespace Planogolny\Analysis\Services;

use Planogolny\Analysis\DTO\AnalysisInputDTO;
use Planogolny\Analysis\DTO\SurroundingsSnapshotDTO;
use Planogolny\GIS\DTO\BuildingDTO;
use Planogolny\GIS\DTO\SurroundingDTO;
use Planogolny\GIS\DTO\SurroundingZoneDTO;

final class ClassificationService
{
    public function classify(SurroundingDTO $surroundings): array
    {
        $scores = [
            'residential' => 0,
            'service' => 0,
            'industrial' => 0,
            'green' => 0,
        ];

        $this->applyZone($surroundings->near, 0.5, $scores);
        $this->applyZone($surroundings->mid, 0.3, $scores);
        $this->applyZone($surroundings->far, 0.2, $scores);

        return $scores;
    }

    private function applyZone(
        SurroundingZoneDTO $zone,
        float $weight,
        array &$scores
    ): void {
        $scores['residential'] +=
            ($zone->residentialSingleCount * 1.0
                + $zone->residentialMultiCount * 0.8)
            * $weight;

        $scores['service'] +=
            $zone->serviceCount * 1.2 * $weight;

        $scores['industrial'] +=
            $zone->industrialCount * 1.5 * $weight;

        $scores['green'] +=
            $zone->greenCount * 0.6 * $weight;
    }
}
