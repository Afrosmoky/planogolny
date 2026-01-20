<?php

namespace Planogolny\Analysis\DTO;

use Planogolny\GIS\DTO\SurroundingDTO;

final readonly class ResultStoreDTO
{
    public function __construct(
        public LegalConstraintsDTO $legalConstraints,
        public String $gusData,
        public SurroundingDTO $surroundings,
        public Array $meta
    ) {}
}
