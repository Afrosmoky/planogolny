<?php

namespace Planogolny\GIS\Services;

use Planogolny\GIS\DTO\CoordinatesDTO;
use Planogolny\GIS\DTO\ParcelDTO;
use Planogolny\GIS\DTO\SurroundingDTO;
class GISFacade
{
    public function __construct(
        protected ?GoogleGeocodingProvider $google = null,
        protected ?OsmProvider $osm = null,
        protected ?GeoportalProvider $geoportal = null,
        protected ?SurroundingsAggregator $aggregator = null
    ) {
        $this->google = $google ?? new GoogleGeocodingProvider();
        $this->osm = $osm ?? new OsmProvider();
        $this->geoportal = $geoportal ?? new GeoportalProvider();
        $this->aggregator = $aggregator ?? new SurroundingsAggregator();
    }

    public function fetchAllData(CoordinatesDTO $coords): array
    {
        $parcel = $this->geoportal->identifyParcel($coords);

        if (!$parcel->centroid) {
            throw new \RuntimeException('Parcel centroid not available');
        }

        $surroundings = $this->aggregator->aggregate(
            parcelLat: $parcel->centroid['lat'],
            parcelLon: $parcel->centroid['lon'],
            buildings: $this->osm->fetchBuildings($coords),
            roads: $this->osm->fetchRoads($coords),
            rail: $this->osm->fetchRail($coords),
            water: $this->osm->fetchWater($coords)
        );

        return [
            'parcel' => $parcel,
            'surroundings' => $surroundings
        ];
    }
}
