<?php

namespace Planogolny\Reporting\Actions;

use Planogolny\Reporting\DTO\SurroundingsReportDTO;

final class BuildSurroundingsReportAction
{
    public function execute(array $surroundings): SurroundingsReportDTO
    {
        $dto = new SurroundingsReportDTO();

        $dto->hasDevelopment = $surroundings['buildingCount'] > 0;

        if ($dto->hasDevelopment) {
            $dto->developmentDescription =
                'W analizowanym otoczeniu występuje zabudowa.';
        } else {
            $dto->developmentDescription =
                'W analizowanym otoczeniu nie stwierdzono istotnej zabudowy.';
        }

        $single = $surroundings['residentialSingleCount'] ?? 0;
        $multi  = $surroundings['residentialMultiCount'] ?? 0;

        if ($single > 0 && $multi === 0) {
            $dto->bulletPoints[] =
                'W otoczeniu przeważa zabudowa mieszkaniowa jednorodzinna.';
        }

        if ($multi > 0 && $single === 0) {
            $dto->bulletPoints[] =
                'W otoczeniu występuje zabudowa mieszkaniowa wielorodzinna.';
        }

        if ($single > 0 && $multi > 0) {
            $dto->bulletPoints[] =
                'W otoczeniu występuje zróżnicowana zabudowa mieszkaniowa (jedno- i wielorodzinna).';
        }

        if ($surroundings['serviceCount'] > 0) {
            $dto->bulletPoints[] =
                'W otoczeniu występują obiekty usługowe.';
        }

        if ($surroundings['industrialCount'] > 0) {
            $dto->bulletPoints[] =
                'W otoczeniu występują obiekty o charakterze przemysłowym.';
        }

        if ($surroundings['hasMainRoad']) {
            $dto->bulletPoints[] =
                'W pobliżu działki przebiega droga o znaczeniu ponadlokalnym.';
        }

        if ($surroundings['hasRail']) {
            $dto->bulletPoints[] =
                'W pobliżu działki przebiega infrastruktura kolejowa.';
        }

        if ($surroundings['hasWater']) {
            $dto->bulletPoints[] =
                'W otoczeniu działki znajdują się cieki lub zbiorniki wodne.';
        }

        if (empty($dto->bulletPoints)) {
            $dto->summary =
                'Otoczenie działki ma charakter niezurbanizowany i nie wykazuje istotnych czynników kolizyjnych.';
        } else {
            $dto->summary =
                'Charakter otoczenia może mieć wpływ na przyszłe zagospodarowanie terenu oraz ustalenia planu ogólnego.';
        }

        return $dto;
    }
}
