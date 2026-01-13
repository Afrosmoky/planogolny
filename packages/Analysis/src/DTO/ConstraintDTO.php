<?php
// packages/Analysis/src/DTO/ConstraintDTO.php

declare(strict_types=1);

namespace Planogolny\Analysis\DTO;

final readonly class ConstraintDTO
{
    public function __construct(
        public bool $exists,
        public ?int $distanceMeters = null,
    ) {}
}
