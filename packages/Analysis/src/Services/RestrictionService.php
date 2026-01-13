<?php

namespace Planogolny\Analysis\Services;

use Planogolny\Analysis\DTO\AnalysisInputDTO;
use Planogolny\Analysis\DTO\ConstraintsDTO;
use Planogolny\GIS\DTO\SurroundingDTO;

final class RestrictionService
{
    /**
     * @return array{
     *   penalties: array<string,int>,
     *   warnings: string[]
     * }
     */
    public function apply(SurroundingDTO $surroundings): array
    {
        $penalties = [];
        $warnings = [];

        if ($input->planRestrictions) {

            if (!$input->planRestrictions->allowResidential) {
                $penalties['residential'] = 100;
                $warnings[] = 'Residential development forbidden by local plan.';
            }

            if (!$input->planRestrictions->allowService) {
                $penalties['service'] = 100;
                $warnings[] = 'Service development forbidden by local plan.';
            }

            if (!$input->planRestrictions->allowIndustrial) {
                $penalties['industrial'] = 100;
                $warnings[] = 'Industrial development forbidden by local plan.';
            }
        }

        if ($surroundings->near->hasRail) {
            $penalties['residential'] = 20;
            $warnings[] = 'Railway line in close proximity';
        }

        if ($surroundings->near->hasMainRoad) {
            $penalties['residential'] =
                ($penalties['residential'] ?? 0) + 10;

            $warnings[] = 'Main road near the parcel';
        }

        if ($surroundings->near->hasWater) {
            $penalties['residential'] =
                ($penalties['residential'] ?? 0) + 15;

            $warnings[] = 'Water body nearby';
        }

        return [
            'penalties' => $penalties,
            'warnings' => $warnings,
        ];
    }
}
