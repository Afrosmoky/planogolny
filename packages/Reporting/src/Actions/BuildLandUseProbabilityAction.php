<?php

namespace Planogolny\Reporting\Actions;

final class BuildLandUseProbabilityAction
{
    public function execute(array $surroundings): array
    {
        // 1️⃣ HARD FALLBACK – brak danych OSM
        $noBuildingData = ($surroundings['buildingCount'] ?? 0) === 0;

        if ($noBuildingData) {
            return [
                'residential_single' => 50,
                'residential_multi'  => 0,
                'service'            => 0,
                'industrial'         => 0,
                'green'              => 50,
            ];
        }

        // 2️⃣ LICZENIE PUNKTÓW
        $points = [
            'residential_single' => 0,
            'residential_multi'  => 0,
            'service'            => 0,
            'industrial'         => 0,
            'green'              => 0,
        ];

        // zabudowa w otoczeniu
        if (($surroundings['residentialCount'] ?? 0) > 0) {
            $points['residential_single'] += 3;
            $points['residential_multi']  += 2;
        }

        if (($surroundings['serviceCount'] ?? 0) > 0) {
            $points['service'] += 2;
        }

        if (($surroundings['industrialCount'] ?? 0) > 0) {
            $points['industrial'] += 2;
        }

        // brak zabudowy = tereny zielone
        if (($surroundings['buildingCount'] ?? 0) === 0) {
            $points['green'] += 3;
        }

        // infrastruktura
        if (!empty($surroundings['hasMainRoad'])) {
            $points['service'] += 1;
            $points['industrial'] += 1;
        }

        if (!empty($surroundings['hasRail'])) {
            $points['industrial'] += 2;
        }

        if (!empty($surroundings['hasWater'])) {
            $points['green'] += 2;
        }

        // 3️⃣ PODZIAŁ PROCENTOWY (mieszana zabudowa)
        $totalUrbanPoints =
            $points['residential_single']
            + $points['residential_multi']
            + $points['service']
            + $points['industrial'];

        $result = [
            'residential_single' => 0,
            'residential_multi'  => 0,
            'service'            => 0,
            'industrial'         => 0,
            'green'              => 0,
        ];

        if ($totalUrbanPoints > 0) {
            foreach (['residential_single', 'residential_multi', 'service', 'industrial'] as $key) {
                $result[$key] = round(
                    ($points[$key] / $totalUrbanPoints) * 100
                );
            }
        }

        // 4️⃣ ZIELONE jako dopełnienie do 100%
        $used = array_sum($result);
        $result['green'] = max(0, 100 - $used);

        return $result;
    }
}
