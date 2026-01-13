<?php

declare(strict_types=1);

namespace Planogolny\GIS\DTO;

final readonly class SurroundingZoneDTO
{
    public function __construct(
        public int $residentialSingleCount,
        public int $residentialMultiCount,
        public int $serviceCount,
        public int $industrialCount,
        public int $greenCount,

        public bool $hasMainRoad,
        public bool $hasRail,
        public bool $hasWater
    ) {}
}
