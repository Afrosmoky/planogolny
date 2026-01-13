<?php

namespace Planogolny\GIS\DTO;

final readonly class BuildingDTO
{
    public function __construct(
        public array $geometry,
        public ?string $type,
        public ?float $area = null,
        public array $raw = []
    ) {}
}
