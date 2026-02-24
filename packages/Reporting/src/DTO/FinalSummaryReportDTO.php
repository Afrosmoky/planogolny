<?php

declare(strict_types=1);

namespace Planogolny\Reporting\DTO;

final class FinalSummaryReportDTO
{
    public string $headline;
    public string $body;
    public string $callToAction;

    public function toArray(): array
    {
        return [
            'headline' => $this->headline,
            'body' => $this->body,
            'callToAction' => $this->callToAction,
        ];
    }
}
