<?php

declare(strict_types=1);

namespace Planogolny\Analysis\Actions;

use Planogolny\Analysis\DTO\AnalysisInputDTO;
use Planogolny\Analysis\Providers\DemographyProvider;
use Planogolny\GIS\Facades\GISFacade;
use Planogolny\GIS\DTO\CoordinatesDTO;
use Planogolny\Analysis\DTO\DemographyDTO;
use Planogolny\GIS\Providers\PlanInfoProvider;
use Planogolny\Analysis\Mappers\MpzPlanMapper;

final readonly class PrepareAnalysisInputAction
{
    public function __construct(
        private GISFacade $gis,
        private PlanInfoProvider $plans,
        private DemographyProvider $demography,
        private MpzPlanMapper $planMapper,
    ) {}

    public function execute(float $lat, float $lon): AnalysisInputDTO
    {
        $coords = new CoordinatesDTO($lat, $lon);

        $data = $this->gis->fetchAllData($coords);

        $planInfo = $this->plans->fetch($coords);
        $planRestrictions = $planInfo->hasPlan
            ? $this->planMapper->map($planInfo)
            : null;

        $demography = $this->demography->fetch($data['parcel']->gmina);

        return new AnalysisInputDTO(
            parcel: $data['parcel'],
            surroundings: $data['surroundings'],
            demography: $demography,
            planRestrictions: $planRestrictions
        );
    }
}
