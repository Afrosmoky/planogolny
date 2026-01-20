<?php

namespace Planogolny\Reporting\DTO;

final class LegalConstraintsReportDTO
{
    public bool $hasAnyRestrictions = false;

    /** @var string[] */
    public array $bulletPoints = [];

    /** @var string[] */
    public array $legalBasis = [];

    public string $summary;
}
