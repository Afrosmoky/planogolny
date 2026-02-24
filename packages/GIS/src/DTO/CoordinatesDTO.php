<?php

declare(strict_types=1);

namespace Planogolny\GIS\DTO;

final readonly class CoordinatesDTO
{
    public function __construct(
        public float $lat,
        public float $lon
    ) {}
}
