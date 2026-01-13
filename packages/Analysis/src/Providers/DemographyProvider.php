<?php

declare(strict_types=1);

namespace Planogolny\Analysis\Providers;

use GuzzleHttp\Client;
use Planogolny\Analysis\DTO\DemographyDTO;

final class DemographyProvider
{
    private Client $http;

    public function __construct(?Client $client = null)
    {
        $this->http = $client ?? new Client([
            'base_uri' => 'https://bdl.stat.gov.pl/api/v1/',
            'timeout' => 15,
        ]);

        //Client ID - uzupełnić to o klient ID bo chyba powinien być w zapytaniu
    }

    public function fetch(?string $gminaCode): ?DemographyDTO
    {
        if (!$gminaCode) {
            return null;
        }

        try {
            // szkic – docelowo konkretne wskaźniki
            $response = $this->http->get("data/by-unit/{$gminaCode}");

            $data = json_decode((string) $response->getBody(), true);

            return new DemographyDTO(
                populationTrend5yPercent: 2.1,
                populationTrend10yPercent: 5.4,
                servicesGrowth: true,
                industrialGrowth: false
            );
        } catch (\Throwable $e) {
            return null;
        }
    }
}
