<?php

declare(strict_types=1);

namespace Planogolny\GIS\Services;

use Planogolny\GIS\DTO\SurroundingDTO;
use Planogolny\GIS\DTO\SurroundingZoneDTO;

final class SurroundingsAggregator
{
    private const NEAR_RADIUS = 50;
    private const MID_RADIUS  = 500;
    private const FAR_RADIUS  = 800;

    public function aggregate(
        float $parcelLat,
        float $parcelLon,
        array $buildings,
        array $roads,
        array $rail,
        array $water
    ): SurroundingDTO {
        $near = $this->emptyCounters();
        $mid  = $this->emptyCounters();
        $far  = $this->emptyCounters();

        foreach ($buildings as $building) {
            $distance = $this->distance(
                $parcelLat,
                $parcelLon,
                $building['lat'],
                $building['lon']
            );

            $arr = match (true) {
                $distance <= self::NEAR_RADIUS => $near,
                $distance <= self::MID_RADIUS => $mid,
                $distance <= self::FAR_RADIUS => $far,
                default => null,
            };
            $zone =& $arr;

            if ($zone === null) {
                continue;
            }

            match ($building['type'] ?? null) {
                'residential_single' => $zone['residential_single']++,
                'residential_multi'  => $zone['residential_multi']++,
                'service'            => $zone['service']++,
                'industrial'         => $zone['industrial']++,
                'green'              => $zone['green']++,
                default              => null,
            };
        }

        foreach ($roads as $road) {
            $distance = $this->distance(
                $parcelLat,
                $parcelLon,
                $road['lat'],
                $road['lon']
            );

            if ($distance <= self::NEAR_RADIUS) {
                if (($road['class'] ?? null) === 'main') {
                    $near->hasMainRoad = true;
                }
            }
        }

        foreach ($rail as $r) {
            $distance = $this->distance(
                $parcelLat,
                $parcelLon,
                $r['lat'],
                $r['lon']
            );

            if ($distance <= self::NEAR_RADIUS) {
                $near->hasRail = true;
            }
        }

        foreach ($water as $w) {
            $distance = $this->distance(
                $parcelLat,
                $parcelLon,
                $w['lat'],
                $w['lon']
            );

            if ($distance <= self::NEAR_RADIUS) {
                $near->hasWater = true;
            }
        }

        return new SurroundingDTO(
            near: $this->toZoneDTO($near),
            mid:  $this->toZoneDTO($mid),
            far:  $this->toZoneDTO($far)
        );
    }

    private function toZoneDTO(array $counters): SurroundingZoneDTO
    {
        return new SurroundingZoneDTO(
            residentialSingleCount: $counters['residential_single'],
            residentialMultiCount:  $counters['residential_multi'],
            serviceCount:           $counters['service'],
            industrialCount:        $counters['industrial'],
            greenCount:             $counters['green'],
            hasMainRoad:            $counters['hasMainRoad'],
            hasRail:                $counters['hasRail'],
            hasWater:               $counters['hasWater']
        );
    }

    private function resolveZone(float $distance): ?SurroundingZoneDTO
    {
        if ($distance <= self::NEAR_RADIUS) {
            return $this->near ??= $this->emptyZone();
        }

        if ($distance <= self::MID_RADIUS) {
            return $this->mid ??= $this->emptyZone();
        }

        if ($distance <= self::FAR_RADIUS) {
            return $this->far ??= $this->emptyZone();
        }

        return null;
    }

    private function emptyZone(): SurroundingZoneDTO
    {
        return new SurroundingZoneDTO(
            residentialSingleCount: 0,
            residentialMultiCount: 0,
            serviceCount: 0,
            industrialCount: 0,
            greenCount: 0,
            hasMainRoad: false,
            hasRail: false,
            hasWater: false
        );
    }

    /**
     * Haversine formula
     */
    private function distance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371000; // meters

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) ** 2 +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function emptyCounters(): array
    {
        return [
            'residential_single' => 0,
            'residential_multi' => 0,
            'service' => 0,
            'industrial' => 0,
            'green' => 0,
            'hasMainRoad' => false,
            'hasRail' => false,
            'hasWater' => false,
        ];
    }
}
