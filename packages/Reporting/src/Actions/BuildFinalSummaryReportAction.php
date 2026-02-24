<?php

declare(strict_types=1);

namespace Planogolny\Reporting\Actions;

use Planogolny\Reporting\DTO\FinalSummaryReportDTO;

final class BuildFinalSummaryReportAction
{
    public function execute(
        array $surroundings,
        array $legalConstraints
    ): FinalSummaryReportDTO {
        $dto = new FinalSummaryReportDTO();

        $dto->headline = 'Podsumowanie analizy działki';

        $hasRestrictions = collect($legalConstraints)
            ->filter(fn ($v, $k) => str_ends_with($k, 'Restriction') && $v === true)
            ->isNotEmpty();

        if (!$hasRestrictions && $surroundings['buildingCount'] > 0) {
            $dto->body =
                'Analiza uwarunkowań przestrzennych wskazuje, że działka znajduje się w otoczeniu o istniejącej zabudowie i nie wykazuje istotnych konfliktów wynikających z przepisów odrębnych. '
                . 'Taki charakter terenu może sprzyjać przeznaczeniu pod funkcje zabudowy w planie ogólnym.';
        } elseif ($hasRestrictions) {
            $dto->body =
                'Analiza uwarunkowań przestrzennych wskazuje na występowanie czynników, które mogą ograniczać sposób zagospodarowania terenu. '
                . 'Uwarunkowania te nie wykluczają możliwości uwzględnienia terenu w planie ogólnym, lecz wymagają ich odpowiedniego rozpoznania i uwzględnienia.';
        } else {
            $dto->body =
                'Analiza uwarunkowań przestrzennych nie wykazała istotnej zabudowy w otoczeniu działki, co może wskazywać na obszar o charakterze niezurbanizowanym. '
                . 'W takim przypadku kluczowe znaczenie mogą mieć kierunki rozwoju gminy oraz wnioski składane do planu ogólnego.';
        }

        $dto->callToAction =
            'Wyniki niniejszego raportu mogą stanowić merytoryczną podstawę do przygotowania wniosku '
            . 'w toku sporządzania planu ogólnego gminy.';

        return $dto;
    }
}
