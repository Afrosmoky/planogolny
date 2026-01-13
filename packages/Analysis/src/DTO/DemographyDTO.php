<?php
// packages/Analysis/src/DTO/DemographyDTO.php

declare(strict_types=1);

namespace Planogolny\Analysis\DTO;

final readonly class DemographyDTO
{
    public function __construct(
        public float $populationTrend5yPercent,
        public float $populationTrend10yPercent,
        public bool $servicesGrowth,
        public bool $industrialGrowth,
    ) {}
}
