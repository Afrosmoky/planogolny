<?php

declare(strict_types=1);

namespace Planogolny\Analysis\Actions;

use Planogolny\Analysis\DTO\LegalConstraintsDTO;
use Planogolny\GIS\DTO\CoordinatesDTO;

final readonly class CalculateLegalConstraintsAction
{
    public function execute(
        CoordinatesDTO $coords,
        array $osmResponses
    ): LegalConstraintsDTO {
        $dto = new LegalConstraintsDTO();

        foreach ($osmResponses['water'] ?? [] as $el) {
            $d = $this->distance($coords->lat, $coords->lon, $el);
            $dto->waterDistance = min($dto->waterDistance ?? INF, $d);

            if ($d < 5) {
                $dto->waterRestriction = true;
            }
        }

        foreach ($osmResponses['embankment'] ?? [] as $el) {
            $d = $this->distance($coords->lat, $coords->lon, $el);
            $dto->embankmentDistance = min($dto->embankmentDistance ?? INF, $d);

            if ($d < 50) {
                $dto->embankmentRestriction = true;
            }
        }

        foreach ($osmResponses['rail'] ?? [] as $el) {
            $d = $this->distance($coords->lat, $coords->lon, $el);
            $dto->railDistance = min($dto->railDistance ?? INF, $d);

            if ($d < 10) {
                $dto->railRestriction = true;
            }
        }

        foreach ($osmResponses['motorway'] ?? [] as $el) {
            $d = $this->distance($coords->lat, $coords->lon, $el);
            $dto->motorwayDistance = min($dto->motorwayDistance ?? INF, $d);

            if ($d < 20) {
                $dto->motorwayRestriction = true;
            }
        }

        foreach ($osmResponses['power'] ?? [] as $el) {
            $d = $this->distance($coords->lat, $coords->lon, $el);
            $dto->powerLineDistance = min($dto->powerLineDistance ?? INF, $d);

            if ($d < 15) {
                $dto->powerLineRestriction = true;
            }
        }

        foreach ($osmResponses['cemetery'] ?? [] as $el) {
            $d = $this->distance($coords->lat, $coords->lon, $el);
            $dto->cemeteryDistance = min($dto->cemeteryDistance ?? INF, $d);

            if ($d < 50) {
                $dto->cemeteryRestriction = true;
            }
        }

        return $dto;
    }

    private function distance(float $lat, float $lon, array $el): float
    {
        if (!isset($el['center'])) {
            return INF;
        }

        return $this->haversine(
            $lat,
            $lon,
            $el['center']['lat'],
            $el['center']['lon']
        );
    }

    private function haversine($lat1, $lon1, $lat2, $lon2): float
    {
        $earth = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        return $earth * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
