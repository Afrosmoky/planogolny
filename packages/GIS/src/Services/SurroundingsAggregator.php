<?php

declare(strict_types=1);

namespace Planogolny\GIS\Services;

use Planogolny\GIS\DTO\SurroundingDTO;

final class SurroundingsAggregator
{
    private const CONTEXT_RADIUS = 200;

    public function aggregate(
        float $parcelLat,
        float $parcelLon,
        array $buildings,
        array $roads,
        array $rail,
        array $water
    ): SurroundingDTO
    {
        $buildingCount = 0;
        $residentialSingle = 0;
        $residentialMulti  = 0;
        $service = 0;
        $industrial = 0;

        foreach ($buildings as $b) {
            $d = $this->distance(
                $parcelLat,
                $parcelLon,
                $b['lat'],
                $b['lon']
            );

            if ($d > self::CONTEXT_RADIUS) {
                continue;
            }

            $buildingCount++;

            match ($b['type'] ?? null) {
                'residential_single' => $residentialSingle++,
                'residential_multi'  => $residentialMulti++,
                'residential'        => $residentialSingle++,
                'service'            => $service++,
                'industrial'         => $industrial++,
                default              => null,
            };
        }

        $hasMainRoad = false;
        foreach ($roads as $r) {
            $distance = $this->distance(
                $parcelLat,
                $parcelLon,
                $r['lat'],
                $r['lon']
            );

            logger()->info('ROAD CHECK', [
                'parcel' => [$parcelLat, $parcelLon],
                'road' => [$r['lat'], $r['lon']],
                'distance_m' => $distance,
                'class' => $r['class'] ?? null,
            ]);

            if ($distance <= self::CONTEXT_RADIUS && ($r['class'] ?? null) === 'main') {
                $hasMainRoad = true;
                break;
            }
        }

        $hasRail = $this->hasWithinRadius($parcelLat, $parcelLon, $rail);
        $hasWater = $this->hasWithinRadius($parcelLat, $parcelLon, $water);

        return new SurroundingDTO(
            buildingCount: $buildingCount,
            residentialSingleCount: $residentialSingle,
            residentialMultiCount: $residentialMulti,
            serviceCount: $service,
            industrialCount: $industrial,
            hasMainRoad: $hasMainRoad,
            hasRail: $hasRail,
            hasWater: $hasWater
        );
    }

    private function hasWithinRadius(
        float $lat,
        float $lon,
        array $elements
    ): bool {
        foreach ($elements as $el) {
            if (
                $this->distance($lat, $lon, $el['lat'], $el['lon']) <= self::CONTEXT_RADIUS
            ) {
                return true;
            }
        }

        return false;
    }

    private function distance(float $lat1, float $lon1, float $lat2, float $lon2): float
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
