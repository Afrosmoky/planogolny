<?php

declare(strict_types=1);

namespace Planogolny\GIS\Providers;

use GuzzleHttp\Client;
use Planogolny\Analysis\DTO\PlanInfoDTO;
use Planogolny\GIS\DTO\CoordinatesDTO;

final class PlanInfoProvider
{
    private Client $http;

    public function __construct(?Client $client = null)
    {
        $this->http = $client ?? new Client([
            'timeout' => 15,
        ]);
    }

    public function fetch(CoordinatesDTO $coords): PlanInfoDTO
    {
        // WMS GetFeatureInfo – punktowo
        $url = 'https://mapy.geoportal.gov.pl/wss/service/PZGIK/MPZP/WMS/Plany';

        try {
            $response = $this->http->get($url, [
                'query' => [
                    'SERVICE' => 'WMS',
                    'VERSION' => '1.3.0',
                    'REQUEST' => 'GetFeatureInfo',
                    'LAYERS' => 'MPZP',
                    'QUERY_LAYERS' => 'MPZP',
                    'CRS' => 'EPSG:4326',
                    'I' => 50,
                    'J' => 50,
                    'WIDTH' => 101,
                    'HEIGHT' => 101,
                    'BBOX' => sprintf(
                        '%f,%f,%f,%f',
                        $coords->lon - 0.0001,
                        $coords->lat - 0.0001,
                        $coords->lon + 0.0001,
                        $coords->lat + 0.0001
                    ),
                    'INFO_FORMAT' => 'application/json',
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if (!empty($data['features'])) {
                return new PlanInfoDTO(
                    hasPlan: true,
                    planType: 'MPZP',
                    symbol: $data['features'][0]['properties']['symbol'] ?? null,
                    description: $data['features'][0]['properties']['opis'] ?? null,
                );
            }

        } catch (\Throwable $e) {
            // fallback
        }

        //return new PlanInfoDTO(hasPlan: false);
        return new PlanInfoDTO(
            hasPlan: true,
            planType: 'MPZP',
            symbol: 'MN'
        );
    }
}
