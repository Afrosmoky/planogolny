<?php

declare(strict_types=1);

namespace Planogolny\Analysis\DTO;

final readonly class PlanInfoDTO
{
    public function __construct(
        public bool $hasPlan,
        public ?string $planType = null, // MPZP | STUDIUM | POG
        public ?string $symbol = null,
        public ?string $description = null,
        public ?string $source = null, // WMS
    ) {}
}
