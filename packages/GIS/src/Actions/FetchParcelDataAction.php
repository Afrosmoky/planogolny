<?php

namespace Planogolny\GIS\Actions;

use Planogolny\GIS\DTO\CoordinatesDTO;
use Planogolny\GIS\Services\GisFacade;
final readonly class FetchParcelDataAction
{
    public function __construct(
        protected GisFacade $gis
    ) {}

    public function execute(CoordinatesDTO $coords)
    {
        // Pipeline: geocode → OSM → Geoportal → PRG (optional)
        return $this->gis->fetchAllData($coords);
    }
}
