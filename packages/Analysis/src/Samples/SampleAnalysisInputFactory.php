<?php

declare(strict_types=1);

namespace Planogolny\Analysis\Samples;

use Planogolny\Analysis\DTO\AnalysisInputDTO;
use Planogolny\Analysis\DTO\DemographyDTO;
use Planogolny\GIS\DTO\ParcelDTO;
use Planogolny\GIS\DTO\SurroundingDTO;
use Planogolny\GIS\DTO\SurroundingZoneDTO;

final class SampleAnalysisInputFactory
{
    public static function suburbanParcel(): AnalysisInputDTO
    {
        return new AnalysisInputDTO(
            parcel: new ParcelDTO(
                gmina: 'Piaseczno',
                powiat: 'piaseczyński',
                wojewodztwo: 'mazowieckie',
                parcelId: '141803_2.0001.123/4'
            ),
            surroundings: new SurroundingDTO(
                near: new SurroundingZoneDTO(
                    residentialSingleCount: 6,
                    residentialMultiCount: 0,
                    serviceCount: 1,
                    industrialCount: 0,
                    greenCount: 2,
                    hasMainRoad: false,
                    hasRail: false,
                    hasWater: false
                ),
                mid: new SurroundingZoneDTO(
                    residentialSingleCount: 18,
                    residentialMultiCount: 2,
                    serviceCount: 3,
                    industrialCount: 0,
                    greenCount: 7,
                    hasMainRoad: true,
                    hasRail: false,
                    hasWater: false
                ),
                far: new SurroundingZoneDTO(
                    residentialSingleCount: 45,
                    residentialMultiCount: 6,
                    serviceCount: 8,
                    industrialCount: 1,
                    greenCount: 20,
                    hasMainRoad: true,
                    hasRail: true,
                    hasWater: true
                )
            ),
            demography: new DemographyDTO(
                populationTrend5yPercent: 4.5,
                populationTrend10yPercent: 9.2,
                servicesGrowth: true,
                industrialGrowth: false
            )
        );
    }
}
