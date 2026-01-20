<?php

namespace Planogolny\Analysis\DTO;

use Planogolny\GIS\DTO\ParcelDTO;
use Planogolny\GIS\DTO\SurroundingDTO;
final readonly class AnalysisInputDTO
{
    public function __construct(
        //public ParcelDTO $parcel,
        public SurroundingDTO $surroundings,
        public LegalConstraintsDTO $legalConstraints,
        //public ?DemographyDTO $demography = null,
    ) {}
}
