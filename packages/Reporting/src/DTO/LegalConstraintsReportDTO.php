<?php

declare(strict_types=1);

namespace Planogolny\Reporting\DTO;

final class LegalConstraintsReportDTO
{
    public bool $hasAnyRestrictions = false;

    /** @var string[] */
    public array $bulletPoints = [];

    /** @var string[] */
    public array $legalBasis = [];

    public string $summary;

    public function toArray(): array
    {
        return [
            'hasAnyRestrictions' => $this->hasAnyRestrictions,
            'bulletPoints' => $this->bulletPoints,
            'legalBasis' => $this->legalBasis,
            'summary' => $this->summary,
        ];
    }
}
