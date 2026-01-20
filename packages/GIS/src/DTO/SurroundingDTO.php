<?php

namespace Planogolny\GIS\DTO;

final readonly class SurroundingDTO
{
    public function __construct(
        public int $buildingCount,
        public int $residentialCount,
        public int $serviceCount,
        public int $industrialCount,
        public bool $hasMainRoad,
        public bool $hasRail,
        public bool $hasWater
    ) {}

    public function toArray(): array
    {
        return [
            'buildingCount' => $this->buildingCount,
            'residentialCount' => $this->residentialCount,
            'serviceCount' => $this->serviceCount,
            'industrialCount' => $this->industrialCount,
            'hasMainRoad' => $this->hasMainRoad,
            'hasRail' => $this->hasRail,
            'hasWater' => $this->hasWater,
        ];
    }
}
