<?php

namespace Planogolny\GIS\Services;

use GuzzleHttp\Client;
use Planogolny\GIS\DTO\CoordinatesDTO;
final class OsmProvider
{
    private Client $http;
    public function __construct(?Client $client = null)
    {
        $this->http = $client ?? new Client([
            'base_uri' => 'https://overpass-api.de',
            'timeout'  => 20,
        ]);
    }
    public function fetchBuildings(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json];
(
  way["building"](around:200,{$coords->lat},{$coords->lon});
  relation["building"](around:200,{$coords->lat},{$coords->lon});
);
out center;
QL;

        $response = $this->callOverpass($query);
        //dd(array_keys($response), $response['elements'][0] ?? null);

        $results = [];

//        if(empty($results)) {
//            return $this->fixtureBuildings();
//        }

        foreach ($response['elements'] ?? [] as $el) {
            if (!isset($el['center']['lat'], $el['center']['lon'])) {
                continue;
            }

            $buildingTag = $el['tags']['building'] ?? null;
            $shopTag = $el['tags']['shop'] ?? null;

            $type = match (true) {
                in_array($buildingTag, [
                    'house',
                    'detached',
                    'residential',
                    'terrace',
                    'semidetached_house'
                ]) => 'residential_single',

                in_array($buildingTag, [
                    'apartments',
                    'block',
                    'dormitory'
                ]) => 'residential_multi',

                in_array($buildingTag, [
                    'retail',
                    'commercial',
                    'office',
                    'supermarket'
                ]) || in_array($shopTag, [
                    'mall',
                    'supermarket',
                    'retail'
                ]) => 'service',

                in_array($buildingTag, [
                    'industrial',
                    'warehouse',
                    'factory'
                ]) => 'industrial',

                default => null,
            };

            if ($type === null) {
                continue;
            }

            $results[] = [
                'lat' => (float) $el['center']['lat'],
                'lon' => (float) $el['center']['lon'],
                'type' => $type,
            ];
        }

        return $results;
    }

    /**
     * Low-level Overpass API call
     */
    private function callOverpass(string $query): array
    {
        $response = $this->http->post('/api/interpreter', [
            'form_params' => [
                'data' => $query,
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $body = (string) $response->getBody();

        $decoded = json_decode($body, true);

        if (!is_array($decoded)) {
            throw new \RuntimeException('Invalid Overpass response');
        }

        return $decoded;
    }

    public function fetchRoads(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json];
(
  way["highway"](around:1000,{$coords->lat},{$coords->lon});
);
out center;
QL;

        $response = $this->callOverpass($query);
        $results = [];

        foreach ($response['elements'] ?? [] as $el) {
            if (!isset($el['center'])) {
                continue;
            }

            $highway = $el['tags']['highway'] ?? null;
            if (!$highway) {
                continue;
            }

            $class = match (true) {
                in_array($highway, ['motorway','trunk','primary']) => 'main',
                in_array($highway, ['secondary','tertiary']) => 'secondary',
                default => 'local',
            };

            $results[] = [
                'lat' => (float) $el['center']['lat'],
                'lon' => (float) $el['center']['lon'],
                'class' => $class,
            ];
        }

        return $results;
    }

    public function fetchRail(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json];
(
  way["railway"](around:2000,{$coords->lat},{$coords->lon});
);
out center;
QL;

        $response = $this->callOverpass($query);
        $results = [];

        foreach ($response['elements'] ?? [] as $el) {
            if (!isset($el['center'])) {
                continue;
            }

            $results[] = [
                'lat' => (float) $el['center']['lat'],
                'lon' => (float) $el['center']['lon'],
            ];
        }

        return $results;
    }

    public function fetchWater(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json];
(
  way["waterway"](around:2000,{$coords->lat},{$coords->lon});
  way["natural"="water"](around:2000,{$coords->lat},{$coords->lon});
);
out center;
QL;

        $response = $this->callOverpass($query);
        $results = [];

        foreach ($response['elements'] ?? [] as $el) {
            if (!isset($el['center'])) {
                continue;
            }

            $results[] = [
                'lat' => (float) $el['center']['lat'],
                'lon' => (float) $el['center']['lon'],
            ];
        }

        return $results;
    }

    private function fixtureBuildings(): array
    {
        return [
            [
                'lat' => 52.0971,
                'lon' => 21.0261,
                'type' => 'residential_single',
            ],
            [
                'lat' => 52.0974,
                'lon' => 21.0264,
                'type' => 'service',
            ],
            [
                'lat' => 52.0978,
                'lon' => 21.0268,
                'type' => 'green',
            ],
        ];
    }
}
