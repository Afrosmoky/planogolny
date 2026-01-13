<?php

namespace Planogolny\Analysis\DTO;

use Planogolny\GIS\DTO\ParcelDTO;
use Planogolny\GIS\DTO\SurroundingDTO;
use Planogolny\Analysis\DTO\PlanRestrictionDTO;
final readonly class AnalysisInputDTO
{
    public function __construct(
        public ParcelDTO $parcel,
        public SurroundingDTO $surroundings,
        public ?DemographyDTO $demography = null,
        public ?PlanRestrictionDTO $planRestrictions = null,
    ) {}
}
