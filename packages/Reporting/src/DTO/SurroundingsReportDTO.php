<?php

namespace Planogolny\Reporting\DTO;

final class SurroundingsReportDTO
{
    public bool $hasDevelopment;
    public string $developmentDescription;

    /** @var string[] */
    public array $bulletPoints = [];

    public string $summary;
}
