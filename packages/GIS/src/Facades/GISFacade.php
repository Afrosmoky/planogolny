<?php

declare(strict_types=1);

namespace Planogolny\GIS\Facades;

use Planogolny\Analysis\Actions\CalculateLegalConstraintsAction;
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

        $rail = $this->osm->fetchRail($coords);
        $water = $this->osm->fetchWater($coords);

        $surroundings = $this->aggregator->aggregate(
            parcelLat: $referencePoint['lat'],
            parcelLon: $referencePoint['lon'],
            buildings: $this->osm->fetchBuildings($coords),
            roads: $this->osm->fetchRoads($coords),
            rail: $rail,
            water: $water,
        );

        $legalConstraints = app(CalculateLegalConstraintsAction::class)->execute(
            $coords,
            array(
                'water' => $water,
                'embankment' => $this->osm->fetchEmbankment($coords),
                'rail' => $rail,
                'motorway' => $this->osm->fetchMotorway($coords),
                'power' => $this->osm->fetchPower($coords),
                'cemetery' => $this->osm->fetchCemetery($coords),
            )
        );

        return [
            'surroundings' => $surroundings,
            'legalConstraints' => $legalConstraints,
        ];
    }
}
