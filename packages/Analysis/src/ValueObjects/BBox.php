<?php
// packages/Analysis/src/ValueObjects/BBox.php

declare(strict_types=1);

namespace Planogolny\Analysis\ValueObjects;

final readonly class BBox
{
    public function __construct(
        public float $minLat,
        public float $minLon,
        public float $maxLat,
        public float $maxLon,
    ) {}

    public static function fromCenter(
        float $lat,
        float $lon,
        float $radiusMeters
    ): self {
        // Przybliżenie – wystarczające dla OSM
        $deltaLat = $radiusMeters / 111_320;
        $deltaLon = $radiusMeters / (111_320 * cos(deg2rad($lat)));

        return new self(
            minLat: $lat - $deltaLat,
            minLon: $lon - $deltaLon,
            maxLat: $lat + $deltaLat,
            maxLon: $lon + $deltaLon,
        );
    }
}
