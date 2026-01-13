<?php

declare(strict_types=1);

namespace Planogolny\GIS\Facades;

use Planogolny\GIS\DTO\CoordinatesDTO;
use Planogolny\GIS\DTO\ParcelDTO;
use Planogolny\GIS\DTO\SurroundingDTO;
use Planogolny\GIS\Services\OsmProvider;
use Planogolny\GIS\Services\SurroundingsAggregator;

final class GISFacade
{
    public function __construct(
        private OsmProvider $osm,
        private SurroundingsAggregator $aggregator,
    ) {}

    public function fetchAllData(CoordinatesDTO $coords): array
    {
        $referencePoint = [
            'lat' => $coords->lat,
            'lon' => $coords->lon,
        ];

        $parcel = new ParcelDTO(
            gmina: null,
            powiat: null,
            wojewodztwo: null,
            parcelId: null,

            geometry: null,                 // brak EGiB
            centroid: $referencePoint,      // punkt analizy = centroid

            raw: [
                'referencePoint' => $referencePoint,
                'source' => 'user_point',
            ]
        );

        $surroundings = $this->aggregator->aggregate(
            parcelLat: $referencePoint['lat'],
            parcelLon: $referencePoint['lon'],
            buildings: $this->osm->fetchBuildings($coords),
            roads: $this->osm->fetchRoads($coords),
            rail: $this->osm->fetchRail($coords),
            water: $this->osm->fetchWater($coords),
        );

        return [
            'parcel' => $parcel,
            'surroundings' => $surroundings,
        ];
    }
}
