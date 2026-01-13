<?php
// packages/Analysis/src/DTO/ConstraintsDTO.php

declare(strict_types=1);

namespace Planogolny\Analysis\DTO;

final readonly class ConstraintsDTO
{
    public function __construct(
        public ConstraintDTO $river,
        public ConstraintDTO $railway,
        public ConstraintDTO $highway,
        public ConstraintDTO $powerLine,
        public ConstraintDTO $cemetery,
    ) {}
}
