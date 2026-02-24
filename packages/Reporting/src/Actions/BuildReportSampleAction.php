<?php

declare(strict_types=1);

namespace Planogolny\Reporting\Actions;

use Planogolny\Analysis\DTO\AnalysisInputDTO;

final class BuildReportSampleAction
{
    public function execute(AnalysisInputDTO $input): array
    {
        $legal = app(BuildLegalConstraintsReportAction::class)
            ->execute($input->legalConstraints);

        $surroundings = app(BuildSurroundingsReportAction::class)
            ->execute($input->surroundings->toArray());

        $finalSummary = app(BuildFinalSummaryReportAction::class)->execute(
            $input->surroundings->toArray(),
            $input->legalConstraints->toArray()
        );

        $landUseProbabilities = app(BuildLandUseProbabilityAction::class)
            ->execute(
                $input->surroundings->toArray(),
            );

        return [
            'legalConstraints' => $legal,
            'surroundings' => $surroundings,
            'finalSummary' => $finalSummary,
            'landUseProbabilities' => $landUseProbabilities,
        ];
    }
}
