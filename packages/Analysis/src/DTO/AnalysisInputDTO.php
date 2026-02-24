<?php

declare(strict_types=1);

namespace Planogolny\Analysis\DTO;

use Planogolny\GIS\DTO\SurroundingDTO;
final readonly class AnalysisInputDTO
{
    public function __construct(
        public SurroundingDTO $surroundings,
        public LegalConstraintsDTO $legalConstraints,
    ) {}

    public function toArray(): array
    {
        return [
            'surroundings' => $this->surroundings->toArray(),
            'legal_constraints' => $this->legalConstraints->toArray(),
        ];
    }
}
