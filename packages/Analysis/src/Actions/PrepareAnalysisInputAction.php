<?php

declare(strict_types=1);

namespace Planogolny\Analysis\Actions;

use App\Models\Analysis;
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
        private DemographyProvider $demography,
    ) {}

    public function execute(Analysis $analysis): AnalysisInputDTO
    {
        $coords = new CoordinatesDTO($analysis->lat, $analysis->lng);
        info('Before fetchAllData from OSM');
        $data = $this->gis->fetchAllData($coords);
        info('Data from OSM: ');
        var_dump($data);
//        $parts = array_map('trim', explode(',', $analysis->address));
//
//        $gmina = count($parts) >= 3
//            ? $parts[count($parts) - 3]
//            : null;
//
//        $demography = $this->demography->fetch($gmina);

        return new AnalysisInputDTO(
            surroundings: $data['surroundings'],
            legalConstraints: $data['legalConstraints'],
        );
    }
}
