<?php

declare(strict_types=1);

namespace Planogolny\GIS\DTO;

final readonly class ParcelDTO
{
    public function __construct(
        public ?string $gmina,
        public ?string $powiat,
        public ?string $wojewodztwo,
        public ?string $parcelId,

        public ?array $geometry,   // GeoJSON Polygon
        public ?array $centroid,   // ['lat' => float, 'lon' => float]

        public array $raw = []
    ) {}
}
