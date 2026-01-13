<?php
// packages/Analysis/src/DTO/SurroundingsSnapshotDTO.php

declare(strict_types=1);

namespace Planogolny\Analysis\DTO;

final readonly class SurroundingsSnapshotDTO
{
    public function __construct(
        public int $residentialSingleCount,
        public int $residentialMultiCount,
        public int $servicesCount,
        public int $industrialCount,
        public int $greenCount,
    ) {}

    public function total(): int
    {
        return
            $this->residentialSingleCount +
            $this->residentialMultiCount +
            $this->servicesCount +
            $this->industrialCount +
            $this->greenCount;
    }
}
