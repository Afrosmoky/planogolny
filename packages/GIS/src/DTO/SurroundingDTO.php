<?php

namespace Planogolny\GIS\DTO;

final readonly class SurroundingDTO
{
    public function __construct(
        public SurroundingZoneDTO $near, // ~50m
        public SurroundingZoneDTO $mid,  // ~100m
        public SurroundingZoneDTO $far   // ~200m
    ) {}
}
