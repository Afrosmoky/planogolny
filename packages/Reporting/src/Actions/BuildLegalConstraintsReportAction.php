<?php

namespace Planogolny\Reporting\Actions;

use Planogolny\Analysis\DTO\LegalConstraintsDTO;
use Planogolny\Reporting\DTO\LegalConstraintsReportDTO;

final class BuildLegalConstraintsReportAction
{
    public function execute(LegalConstraintsDTO $constraints): LegalConstraintsReportDTO
    {
        $report = new LegalConstraintsReportDTO();

        if ($constraints->waterRestriction) {
            $report->hasAnyRestrictions = true;
            $report->bulletPoints[] =
                'W bezpośrednim sąsiedztwie działki występuje ciek lub zbiornik wodny, co może wiązać się z ograniczeniami zagospodarowania terenu.';
            $report->legalBasis[] =
                'Prawo wodne – strefy ochronne cieków i wód powierzchniowych.';
        }

        if ($constraints->embankmentRestriction) {
            $report->hasAnyRestrictions = true;
            $report->bulletPoints[] =
                'W pobliżu działki zlokalizowany jest wał przeciwpowodziowy, co może istotnie ograniczać możliwość zabudowy.';
            $report->legalBasis[] =
                'Prawo wodne – ochrona urządzeń przeciwpowodziowych.';
        }

        if ($constraints->railRestriction) {
            $report->hasAnyRestrictions = true;
            $report->bulletPoints[] =
                'W niewielkiej odległości od działki przebiega linia kolejowa, co może powodować ograniczenia funkcjonalne oraz uciążliwości akustyczne.';
            $report->legalBasis[] =
                'Ustawa o transporcie kolejowym – strefy oddziaływania infrastruktury kolejowej.';
        }

        if ($constraints->motorwayRestriction) {
            $report->hasAnyRestrictions = true;
            $report->bulletPoints[] =
                'W sąsiedztwie działki znajduje się droga o wysokiej przepustowości (autostrada lub droga ekspresowa), co może wiązać się z ograniczeniami odległościowymi i hałasem.';
            $report->legalBasis[] =
                'Ustawa o drogach publicznych – wymagane odległości od dróg krajowych.';
        }

        if ($constraints->powerLineRestriction) {
            $report->hasAnyRestrictions = true;
            $report->bulletPoints[] =
                'W pobliżu działki przebiega napowietrzna linia elektroenergetyczna, co może wprowadzać strefy techniczne wyłączające część terenu z zabudowy.';
            $report->legalBasis[] =
                'Prawo energetyczne – strefy bezpieczeństwa infrastruktury elektroenergetycznej.';
        }

        if ($constraints->cemeteryRestriction) {
            $report->hasAnyRestrictions = true;
            $report->bulletPoints[] =
                'W bliskim sąsiedztwie działki zlokalizowany jest cmentarz, co może skutkować ograniczeniami sanitarnymi.';
            $report->legalBasis[] =
                'Przepisy sanitarne dotyczące lokalizacji cmentarzy.';
        }

        if (!$report->hasAnyRestrictions) {
            $report->summary =
                'Na podstawie przeprowadzonej analizy nie stwierdzono istotnych ograniczeń wynikających z przepisów odrębnych w bezpośrednim otoczeniu działki.';
        } else {
            $report->summary =
                'W otoczeniu działki zidentyfikowano czynniki, które mogą wpływać na sposób jej zagospodarowania i powinny zostać uwzględnione przy sporządzaniu planu ogólnego lub wniosku planistycznego.';
        }

        return $report;
    }
}
