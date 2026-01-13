<?php

declare(strict_types=1);

namespace Planogolny\Analysis\DTO;

final readonly class PlanRestrictionDTO
{
    public function __construct(
        public bool $allowResidential,
        public bool $allowService,
        public bool $allowIndustrial,
        public bool $allowGreen,
        public ?int $maxHeight = null,      // w metrach
        public ?float $maxIntensity = null, // np. 0.5
        public ?string $source = null       // MPZP / STUDIUM / POG
    ) {}
}
