<?php

namespace App\Actions;

use App\Models\AnalysisResult;
use Placeholder\Reporting\Actions\BuildSurroundingsReportAction;
use Planogolny\Reporting\Actions\BuildFinalSummaryReportAction;
use Planogolny\Reporting\Actions\BuildLegalConstraintsReportAction;

final class BuildReportDataAction
{
    public function execute(AnalysisResult $result): array
    {
        $legal = app(BuildLegalConstraintsReportAction::class)
            ->execute($result->legal_constraints);

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
