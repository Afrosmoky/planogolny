<?php

namespace Planogolny\GIS\Services;

use GuzzleHttp\Client;
use Planogolny\GIS\DTO\CoordinatesDTO;
final class OsmProvider
{
    private array $overpassEndpoints = [
        'https://overpass-api.de/api/interpreter',
//        'https://overpass.kumi.systems/api/interpreter',
////        'https://overpass.nchc.org.tw/api/interpreter',
    ];
    private Client $http;
    public function __construct(?Client $client = null)
    {
        $this->http = $client ?? new Client([
            'base_uri' => 'https://overpass-api.de',
            'timeout'  => 50,
        ]);
    }
    public function fetchBuildings(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json][timeout:25];
(
  way["building"](around:200,{$coords->lat},{$coords->lon});
);
out tags center;
QL;

        $response = $this->callOverpass($query);
        //dd(array_keys($response), $response['elements'][0] ?? null);

        $results = [];
        foreach (array_slice($response['elements'] ?? [], 0, 3) as $el) {
            logger()->info('OSM buildings raw element', [
                'id' => $el['id'] ?? null,
                'tags' => $el['tags'] ?? null,
                'center' => $el['center'] ?? null,
            ]);
        }

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


                $buildingTag !== null => 'residential', // building=yes → residential

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
    private function callOverpass(string $query): ?array
    {
        foreach ($this->overpassEndpoints as $endpoint) {
            for ($attempt = 1; $attempt <= 4; $attempt++) {
                try {
                    $response = $this->http->post($endpoint, [
                        'form_params' => [
                            'data' => $query,
                        ],
                        'headers' => [
                            'Accept' => 'application/json',
                        ],
                        'timeout' => 25,
                        'connect_timeout' => 20,
                    ]);

                    $body = (string) $response->getBody();
                    $decoded = json_decode($body, true);

                    if (!is_array($decoded)) {
                        throw new \RuntimeException('Invalid Overpass JSON response');
                    }

                    return $decoded;
                } catch (\Throwable $e) {
                    logger()->warning('Overpass request failed', [
                        'endpoint' => $endpoint,
                        'attempt' => $attempt,
                        'error' => $e->getMessage(),
                    ]);

                    // krótka przerwa przed retry
                    usleep(500_000); // 0.5s
                }
            }
        }

        // wszystkie endpointy padły
        logger()->error('All Overpass endpoints failed', [
            'query_hash' => substr(md5($query), 0, 8),
        ]);

        return null;
    }

    public function fetchRoads(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json][timeout:25];
(
  way["highway"](around:100,{$coords->lat},{$coords->lon});
);
out tags center;
QL;

        $response = $this->callOverpass($query);
        $results = [];

        foreach (array_slice($response['elements'] ?? [], 0, 3) as $el) {
            logger()->info('OSM roads raw element', [
                'id' => $el['id'] ?? null,
                'tags' => $el['tags'] ?? null,
                'center' => $el['center'] ?? null,
            ]);
        }

        foreach ($response['elements'] ?? [] as $el) {

            // highway musi istnieć
            $highway = $el['tags']['highway'] ?? null;
            if (!$highway) {
                continue;
            }

            // wycinamy ścieżki piesze / techniczne
            if (in_array($highway, ['footway', 'path', 'cycleway'])) {
                continue;
            }

            // klasyfikacja domenowa
            $class = match (true) {
                in_array($highway, [
                    'motorway',
                    'trunk',
                    'primary',
                    'secondary',
                    'tertiary',
                ]) => 'main',

                in_array($highway, [
                    'residential',
                    'unclassified',
                ]) => 'local',

                default => 'local',
            };


            $lat = null;
            $lon = null;

            if (isset($el['center']['lat'], $el['center']['lon'])) {
                $lat = (float) $el['center']['lat'];
                $lon = (float) $el['center']['lon'];
            } elseif (!empty($el['geometry'])) {
                // bierzemy pierwszy punkt geometrii (wystarczy do heurystyki)
                $lat = (float) $el['geometry'][0]['lat'];
                $lon = (float) $el['geometry'][0]['lon'];
            } else {
                continue;
            }

            $results[] = [
                'lat' => $lat,
                'lon' => $lon,
                'class' => $class,
            ];

        }

        logger()->info('OSM roads parsed', [
            'count' => count($results),
        ]);

        return $results;
    }

    public function fetchRail(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json][timeout:25];
(
  way["railway" = "rail"](around:200,{$coords->lat},{$coords->lon});
);
out tags center;
QL;

        $response = $this->callOverpass($query);
        $results = [];

        foreach (array_slice($response['elements'] ?? [], 0, 3) as $el) {
            logger()->info('OSM rails raw element', [
                'id' => $el['id'] ?? null,
                'tags' => $el['tags'] ?? null,
                'center' => $el['center'] ?? null,
            ]);
        }

        foreach ($response['elements'] ?? [] as $el) {


            if (!isset($el['tags']['railway'])) {
                continue;
            }

            $lat = null;
            $lon = null;

            // center → geometry → skip
            if (isset($el['center']['lat'], $el['center']['lon'])) {
                $lat = (float) $el['center']['lat'];
                $lon = (float) $el['center']['lon'];
            } elseif (!empty($el['geometry'])) {
                $lat = (float) $el['geometry'][0]['lat'];
                $lon = (float) $el['geometry'][0]['lon'];
            } else {
                continue;
            }

            $results[] = [
                'lat' => $lat,
                'lon' => $lon,
            ];
        }
        logger()->info('OSM rail parsed', ['count' => count($results)]);
        return $results;
    }

    public function fetchWater(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json][timeout:25];
(
  way["waterway"](around:200,{$coords->lat},{$coords->lon});
  way["natural"="water"](around:200,{$coords->lat},{$coords->lon});
);
out tags center;
QL;

        $response = $this->callOverpass($query);
        $results = [];

        foreach (array_slice($response['elements'] ?? [], 0, 3) as $el) {
            logger()->info('OSM rivers raw element', [
                'id' => $el['id'] ?? null,
                'tags' => $el['tags'] ?? null,
                'center' => $el['center'] ?? null,
            ]);
        }

        foreach ($response['elements'] ?? [] as $el) {

            // interesują nas tylko wody
            if (($el['tags']['natural'] ?? null) !== 'water') {
                continue;
            }

            $lat = null;
            $lon = null;

            // center → geometry → skip
            if (isset($el['center']['lat'], $el['center']['lon'])) {
                $lat = (float) $el['center']['lat'];
                $lon = (float) $el['center']['lon'];
            } elseif (!empty($el['geometry'])) {
                $lat = (float) $el['geometry'][0]['lat'];
                $lon = (float) $el['geometry'][0]['lon'];
            } else {
                continue;
            }

            $results[] = [
                'lat' => $lat,
                'lon' => $lon,
            ];
        }
        logger()->info('OSM water parsed', ['count' => count($results)]);
        return $results;
    }

    public function fetchEmbankment(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json][timeout:25];
(
  way["man_made"="embankment"](around:300, {{lat}}, {{lon}});
);
out tags center;
QL;
        $response = $this->callOverpass($query);
        $results = [];

        foreach (array_slice($response['elements'] ?? [], 0, 3) as $el) {
            logger()->info('OSM Embankment raw element', [
                'id' => $el['id'] ?? null,
                'tags' => $el['tags'] ?? null,
                'center' => $el['center'] ?? null,
            ]);
        }

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

    public function fetchPower(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json][timeout:25];
(
  way["highway"="motorway"](around:500, {{lat}}, {{lon}});
  way["highway"="trunk"](around:500, {{lat}}, {{lon}});
);
out tags center;
QL;
        $response = $this->callOverpass($query);
        $results = [];

        foreach (array_slice($response['elements'] ?? [], 0, 3) as $el) {
            logger()->info('OSM Power raw element', [
                'id' => $el['id'] ?? null,
                'tags' => $el['tags'] ?? null,
                'center' => $el['center'] ?? null,
            ]);
        }

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

    public function fetchCemetery(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json][timeout:300, {{lat}}, {{lon}}];
(
  way["landuse"="cemetery"](around:50, {{lat}}, {{lon}});
  way["amenity"="grave_yard"](around:50, {{lat}}, {{lon}});
);
out tags center;
QL;
        $response = $this->callOverpass($query);
        $results = [];

        foreach (array_slice($response['elements'] ?? [], 0, 3) as $el) {
            logger()->info('OSM Cementery raw element', [
                'id' => $el['id'] ?? null,
                'tags' => $el['tags'] ?? null,
                'center' => $el['center'] ?? null,
            ]);
        }

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

    public function fetchMotorway(CoordinatesDTO $coords): array
    {
        $query = <<<QL
[out:json][timeout:25];
(
  way["highway"="motorway"](around:500, {{lat}}, {{lon}});
  way["highway"="trunk"](around:500, {{lat}}, {{lon}});
);
out tags center;
QL;
        $response = $this->callOverpass($query);
        $results = [];

        foreach (array_slice($response['elements'] ?? [], 0, 3) as $el) {
            logger()->info('OSM rails raw element', [
                'id' => $el['id'] ?? null,
                'tags' => $el['tags'] ?? null,
                'center' => $el['center'] ?? null,
            ]);
        }

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
}
