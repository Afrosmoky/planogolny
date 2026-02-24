<?php

declare(strict_types=1);

namespace Planogolny\Analysis\Actions;

use App\Models\Analysis;
use Planogolny\Analysis\DTO\AnalysisInputDTO;
use Planogolny\GIS\Facades\GISFacade;
use Planogolny\GIS\DTO\CoordinatesDTO;


final readonly class PrepareAnalysisInputAction
{
    public function __construct(
        private GISFacade $gis,
    ) {}

    public function execute(Analysis $analysis): AnalysisInputDTO
    {
        $coords = new CoordinatesDTO($analysis->lat, $analysis->lng);
        info('Before fetchAllData from OSM');
        $data = $this->gis->fetchAllData($coords);
        info('Data from OSM: ');

        return new AnalysisInputDTO(
            surroundings: $data['surroundings'],
            legalConstraints: $data['legalConstraints'],
        );
    }
}
