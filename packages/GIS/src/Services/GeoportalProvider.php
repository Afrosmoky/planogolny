<?php

declare(strict_types=1);

namespace Planogolny\GIS\Services;

use GuzzleHttp\Client;
use Planogolny\GIS\DTO\CoordinatesDTO;
use Planogolny\GIS\DTO\ParcelDTO;

final class GeoportalProvider
{
    private Client $http;

    public function __construct(?Client $client = null)
    {
        $this->http = $client ?? new Client([
            'timeout' => 20,
        ]);
    }

    public function identifyParcel(CoordinatesDTO $coords): ParcelDTO
    {
        // WFS – działki ewidencyjne (EGiB)
        $url = 'https://mapy.geoportal.gov.pl/wss/service/PZGIK/EGIB/WFS/DzialkiEwidencyjne';

        $response = $this->http->get($url, [
            'query' => [
                'service' => 'WFS',
                'version' => '2.0.0',
                'request' => 'GetFeature',
                'typeNames' => 'egib:DzialkiEwidencyjne',
                'outputFormat' => 'application/json',
                'srsName' => 'EPSG:4326',
                'CQL_FILTER' => sprintf(
                    'DWITHIN(geometria,POINT(%f %f),0.001,meters)',
                    $coords->lon,
                    $coords->lat
                ),
            ],
        ]);

        $data = json_decode((string) $response->getBody(), true);

        $feature = $data['features'][0] ?? null;

        if (!$feature) {
            return new ParcelDTO(
                gmina: null,
                powiat: null,
                wojewodztwo: null,
                parcelId: null,
                geometry: null,
                centroid: null,
                raw: $data
            );
        }

        $geometry = $feature['geometry'];
        $centroid = $this->calculateCentroid($geometry);

        return new ParcelDTO(
            gmina: $feature['properties']['JPT_NAZWA_'] ?? null,
            powiat: $feature['properties']['POWIAT'] ?? null,
            wojewodztwo: $feature['properties']['WOJ'] ?? null,
            parcelId: $feature['properties']['ID_DZIALKI'] ?? null,
            geometry: $geometry,
            centroid: $centroid,
            raw: $feature
        );
    }

    private function calculateCentroid(array $geometry): ?array
    {
        if ($geometry['type'] !== 'Polygon') {
            return null;
        }

        $coords = $geometry['coordinates'][0];
        $sumLat = 0;
        $sumLon = 0;
        $count = count($coords);

        foreach ($coords as [$lon, $lat]) {
            $sumLat += $lat;
            $sumLon += $lon;
        }

        return [
            'lat' => $sumLat / $count,
            'lon' => $sumLon / $count,
        ];
    }
}
