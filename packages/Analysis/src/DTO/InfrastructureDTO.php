<?php
// packages/Analysis/src/DTO/InfrastructureDTO.php

declare(strict_types=1);

namespace Planogolny\Analysis\DTO;

final readonly class InfrastructureDTO
{
    public function __construct(
        public bool $publicRoadAccess,
        public bool $electricityAvailable,
        public bool $technicalRoadAvailable,
    ) {}
}
