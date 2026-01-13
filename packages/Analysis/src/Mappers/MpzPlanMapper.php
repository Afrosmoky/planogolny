<?php

declare(strict_types=1);

namespace Planogolny\Analysis\Mappers;

use Planogolny\Analysis\DTO\PlanInfoDTO;
use Planogolny\Analysis\DTO\PlanRestrictionDTO;

final class MpzPlanMapper
{
    public function map(PlanInfoDTO $plan): PlanRestrictionDTO
    {
        return match ($plan->symbol) {

            // Zabudowa jednorodzinna
            'MN' => new PlanRestrictionDTO(
                allowResidential: true,
                allowService: false,
                allowIndustrial: false,
                allowGreen: true,
                maxHeight: 9,
                source: $plan->planType
            ),

            // Zabudowa wielorodzinna
            'MW' => new PlanRestrictionDTO(
                allowResidential: true,
                allowService: true,
                allowIndustrial: false,
                allowGreen: false,
                maxHeight: 15,
                source: $plan->planType
            ),

            // Usługi
            'U' => new PlanRestrictionDTO(
                allowResidential: false,
                allowService: true,
                allowIndustrial: false,
                allowGreen: false,
                source: $plan->planType
            ),

            // Przemysł
            'P' => new PlanRestrictionDTO(
                allowResidential: false,
                allowService: false,
                allowIndustrial: true,
                allowGreen: false,
                source: $plan->planType
            ),

            default => new PlanRestrictionDTO(
                allowResidential: true,
                allowService: true,
                allowIndustrial: false,
                allowGreen: true,
                source: $plan->planType
            ),
        };
    }
}
