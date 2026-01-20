<?php

namespace Planogolny\Reporting\Actions;

use App\Models\AnalysisResult;
use Placeholder\Reporting\Actions\BuildSurroundingsReportAction;
use Planogolny\Analysis\DTO\LegalConstraintsDTO;
use Planogolny\Reporting\Actions\BuildFinalSummaryReportAction;
use Planogolny\Reporting\Actions\BuildLegalConstraintsReportAction;

final class BuildReportDataAction
{
    public function execute(AnalysisResult $result): array
    {
        $legalDto = LegalConstraintsDTO::fromArray($result->legal_constraints);

        $legal = app(BuildLegalConstraintsReportAction::class)
            ->execute($legalDto);

        $surroundings = app(BuildSurroundingsReportAction::class)
            ->execute($result->surroundings);

        $finalSummary = app(BuildFinalSummaryReportAction::class)->execute(
            $result->surroundings,
            $result->legal_constraints
        );

        return [
//            'parcel' => $result->parcel,
            'legalConstraints' => $legal,
            'surroundings' => $surroundings,
            'finalSummary' => $finalSummary,
            'meta' => $result->meta,
        ];
    }
}
